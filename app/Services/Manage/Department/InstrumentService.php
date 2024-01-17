<?php

namespace App\Services\Manage\Department;

use App\Models\Department;
use App\Models\Instrument\DepartmentInstrumentType;
use App\Models\Instrument\Instrument;
use Illuminate\Support\Facades\DB;

class InstrumentService
{
    /**
     * @throws \Throwable
     */
    public function create(int $departmentId, int $typeId, string $serial, int $status, ?string $notes = null): Instrument
    {
        $department = Department::findOrFail($departmentId);

        DB::beginTransaction();
        try {
            /* @var $departmentInstrumentType DepartmentInstrumentType */
            if (!($departmentInstrumentType = $department->departmentInstrumentTypes()->where('type_id', $typeId)->first())) {
                $departmentInstrumentType = $department->departmentInstrumentTypes()->create([
                    'type_id' => $typeId,
                    'quantity' => 0,
                ]);
            }

            $instrument = $departmentInstrumentType->instruments()->create([
                'serial' => $serial,
                'status' => $status,
                'notes' => $notes,
            ]);

            $departmentInstrumentType->update([
                'quantity' => $departmentInstrumentType->quantity + 1,
            ]);

            DB::commit();

            return $instrument;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
