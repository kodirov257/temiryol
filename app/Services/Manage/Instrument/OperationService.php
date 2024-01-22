<?php

namespace App\Services\Manage\Instrument;

use App\Helpers\LanguageHelper;
use App\Http\Requests\Admin\Instrument\Operations\CloseRequest;
use App\Http\Requests\Admin\Instrument\Operations\CreateRequest;
use App\Http\Requests\Admin\Instrument\Operations\ProlongRequest;
use App\Http\Requests\Admin\Instrument\Operations\UpdateRequest;
use App\Models\Instrument\Instrument;
use App\Models\Instrument\Operation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OperationService
{
    public const RENT = 'rent';
    public const PROLONG = 'prolong';
    public const CLOSE = 'close';
    public const EDIT = 'edit';

    /**
     * @throws \Throwable
     */
    public function rent(int $instrumentId, CreateRequest $request): Operation
    {
        $instrument = Instrument::findOrFail($instrumentId);
        $departmentInstrumentType = $instrument->departmentInstrumentType()->firstOrFail();

        DB::beginTransaction();
        try {
            $operation = Operation::create([
                'instrument_id' => $instrument->id,
                'borrower_id' => $request->borrower_id,
                'department_id' => $instrument->departmentInstrumentType->department_id,
                'instrument_type_id' => $instrument->departmentInstrumentType->type_id,
                'serial' => $instrument->serial,
                'type' => Operation::TYPE_RENT,
                'status' => Operation::STATUS_ACTIVE,
                'instrument_status' => Instrument::STATUS_IN_USE,
                'deadline' => $request->deadline_date . ' ' . $request->deadline_time . ':00',
                'unique_id' => Str::random(10) . '_' . time(),
                'notes' => $request->notes,
            ]);

            $instrument->rent();
            $instrument->saveOrFail();

            $departmentInstrumentType->updateOrFail([
                'quantity' => $departmentInstrumentType->quantity - 1,
            ]);

            DB::commit();

            return $operation;
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function prolong(int $operationId, ProlongRequest $request): Operation
    {
        $oldOperation = Operation::findOrFail($operationId);
        $instrument = $oldOperation->instrument()->firstOrFail();

        DB::beginTransaction();
        try {
            $oldOperation->updateOrFail([
                'status' => Operation::STATUS_CLOSED,
                'notes' => $request->notes,
            ]);

            $operation = Operation::create([
                'instrument_id' => $oldOperation->instrument_id,
                'borrower_id' => $oldOperation->borrower_id,
                'department_id' => $instrument->departmentInstrumentType->department_id,
                'instrument_type_id' => $instrument->departmentInstrumentType->type_id,
                'serial' => $oldOperation->serial,
                'parent_id' => $oldOperation->id,
                'type' => $oldOperation->type,
                'status' => Operation::STATUS_PROLONGED,
                'instrument_status' => $oldOperation->instrument_status,
                'deadline' => $request->deadline_date . ' ' . $request->deadline_time . ':00',
                'unique_id' => $oldOperation->unique_id,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return $operation;
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function close(int $operationId, CloseRequest $request): Operation
    {
        $operation = Operation::findOrFail($operationId);
        $instrument = $operation->instrument()->firstOrFail();
        $departmentInstrumentType = $instrument->departmentInstrumentType()->firstOrFail();

        DB::beginTransaction();
        try {
            $operation->updateOrFail([
                'status' => Operation::STATUS_CLOSED,
                'notes' => $request->notes,
            ]);

            $instrument->return($request->instrument_status);
            $instrument->saveOrFail();

            if (in_array($request->instrument_status, [Instrument::STATUS_AVAILABLE, Instrument::STATUS_REPAIRED], true)) {
                $departmentInstrumentType->updateOrFail([
                    'quantity' => $departmentInstrumentType->quantity + 1,
                ]);
            }

            DB::commit();

            return $operation;
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, UpdateRequest $request): void
    {
        $operation = Operation::findOrFail($id);

        if (!$operation->isClosed()) {
            $operation->update([
                'borrower_id' => $request->borrower_id,
                'deadline' => $request->deadline_date . ' ' . $request->deadline_time . ':00',
                'notes' => $request->notes,
            ]);
        }
    }


    /**
     * @return Operation[]
     */
    public static function getOperationsGrouped(int $instrumentId, Operation $operation = null, bool $includeItself = true): array
    {
        if ($operation) {
            $operations = [$operation];
        } else {
            $operations = Operation::with(['children'])->where('instrument_id', $instrumentId)->whereNull('parent_id')
                ->orderBy('id')->get();
        }
        $result = [];
        foreach ($operations as $value) {
            if ($includeItself) {
                $value->depth = 0;
                $result[] = $value;
            }
            self::getDescendants($result, $value, $includeItself ? 1 : 0);
        }

        return $result;
    }

    /**
     * @return Operation[]
     */
    public static function getDescendants(array &$operations, Operation $operation, int $depth): array
    {
        foreach ($operation->children as $child) {
            $child->depth = $depth;
            $operations[] = $child;
            self::getDescendants($operations, $child, $depth + 1);
        }
        return $operations;
    }
}
