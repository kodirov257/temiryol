<?php

namespace App\Models\Instrument;

use App\Models\BasePivot;
use App\Models\Department;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $type_id
 * @property int $department_id
 * @property int $quantity
 *
 * @property InstrumentType $type
 * @property Department $department
 *
 * @mixin Eloquent
 */
class DepartmentInstrumentType extends BasePivot
{
    protected $table = 'department_instrument_types';

    protected $fillable = [
        'type_id', 'department_id', 'quantity',
    ];


    ########################################### Relations

    public function type(): BelongsTo|InstrumentType
    {
        return $this->belongsTo(InstrumentType::class, 'type_id', 'id');
    }

    public function department(): BelongsTo|Department
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    ###########################################
}
