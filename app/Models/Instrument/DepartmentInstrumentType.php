<?php

namespace App\Models\Instrument;

use App\Models\BaseModel;
use App\Models\Department;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $type_id
 * @property int $department_id
 * @property int $quantity
 *
 * @property InstrumentType $type
 * @property Department $department
 * @property Instrument[] $instruments
 *
 * @mixin Eloquent
 */
class DepartmentInstrumentType extends BaseModel
{
    protected $table = 'department_instrument_types';

    protected $fillable = [
        'type_id', 'department_id', 'quantity',
    ];

    public $timestamps = false;


    ########################################### Relations

    public function type(): BelongsTo|InstrumentType
    {
        return $this->belongsTo(InstrumentType::class, 'type_id', 'id');
    }

    public function department(): BelongsTo|Department
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function instruments(): HasMany|Instrument
    {
        return $this->hasMany(Instrument::class, 'instrument_type_id', 'id');
    }

    ###########################################
}
