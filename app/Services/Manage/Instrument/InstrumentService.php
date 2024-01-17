<?php

namespace App\Services\Manage\Instrument;

use App\Models\Instrument\DepartmentInstrumentType;
use App\Models\Instrument\Instrument;
use Illuminate\Support\Facades\DB;

class InstrumentService
{
    /**
     * @throws \Throwable
     */
    public function create(int $departmentInstrumentTypeId, string $serial, int $status, ?string $notes = null): Instrument
    {
        $departmentInstrumentType = DepartmentInstrumentType::findOrFail($departmentInstrumentTypeId);

        DB::beginTransaction();
        try {
            $instrument = $departmentInstrumentType->instruments()->create([
                'serial' => $serial,
                'status' => $status,
                'notes' => $notes,
            ]);

            if ($status === Instrument::STATUS_AVAILABLE) {
                $departmentInstrumentType->update([
                    'quantity' => $departmentInstrumentType->quantity + 1,
                ]);
            }

            DB::commit();

            return $instrument;
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function update(int $id, int $departmentInstrumentTypeId, string $serial, int $status, ?string $notes = null): void
    {
        $departmentInstrumentType = DepartmentInstrumentType::findOrFail($departmentInstrumentTypeId);
        $instrument = Instrument::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($status === Instrument::STATUS_REPAIRED && !in_array($instrument->status, [Instrument::STATUS_AVAILABLE, Instrument::STATUS_AVAILABLE], true)) {
                $departmentInstrumentType->updateOrFail([
                    'quantity' => $departmentInstrumentType->quantity + 1,
                ]);
            }

            $instrument->update([
                'serial' => $serial,
                'status' => $status,
                'notes' => $notes ?? $instrument->notes,
            ]);

            DB::commit();
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function lend(int $id, int $departmentInstrumentTypeId, ?string $notes = null): void
    {
        $departmentInstrumentType = DepartmentInstrumentType::findOrFail($departmentInstrumentTypeId);
        $instrument = Instrument::findOrFail($id);

        DB::beginTransaction();
        try {
            $instrument->lend($notes);
            $instrument->saveOrFail();

            $departmentInstrumentType->updateOrFail([
                'quantity' => $departmentInstrumentType->quantity + 1,
            ]);

            DB::commit();
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function return(int $id, int $departmentInstrumentTypeId, int $status, ?string $notes = null): void
    {
        $departmentInstrumentType = DepartmentInstrumentType::findOrFail($departmentInstrumentTypeId);
        $instrument = Instrument::findOrFail($id);

        DB::beginTransaction();
        try {
            $instrument->return($status, $notes);
            $instrument->saveOrFail();

            if ($status === Instrument::STATUS_AVAILABLE) {
                $departmentInstrumentType->updateOrFail([
                    'quantity' => $departmentInstrumentType->quantity + 1,
                ]);
            }

            DB::commit();
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function remove(int $id, int $departmentInstrumentTypeId): void
    {
        $departmentInstrumentType = DepartmentInstrumentType::findOrFail($departmentInstrumentTypeId);
        $instrument = Instrument::findOrFail($id);

        DB::beginTransaction();
        try {
            $status = $instrument->status;
            $instrument->deleteOrFail();

            if ($status === Instrument::STATUS_AVAILABLE) {
                $departmentInstrumentType->updateOrFail([
                    'quantity' => $departmentInstrumentType->quantity - 1,
                ]);
            }

            DB::commit();
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
