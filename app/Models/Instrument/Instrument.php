<?php

namespace App\Models\Instrument;

use App\Helpers\ImageHelper;
use App\Helpers\LanguageHelper;
use App\Models\BaseModel;
use App\Models\Department;
use App\Models\User\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $instrument_type_id
 * @property string $serial
 * @property int $status
 * @property string $notes
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property DepartmentInstrumentType $departmentInstrumentType
 * @property User $createdBy
 * @property User $updatedBy
 *
 * @mixin Eloquent
 */
class Instrument extends BaseModel
{
    public const STATUS_UNAVAILABLE = 1;
    public const STATUS_BROKEN = 5;
    public const STATUS_AVAILABLE = 10;
    public const STATUS_REPAIRED = 11;
    public const STATUS_IN_USE = 12;
    public const STATUS_NOT_RETURNED = 13;

    protected $table = 'instrument_instruments';

    public int $depth;

    protected $fillable = [
        'instrument_type_id', 'serial', 'status', 'notes',
    ];

    public static function statusList(): array
    {
        return [
            self::STATUS_UNAVAILABLE => trans('adminlte.instrument.unavailable'),
            self::STATUS_BROKEN => trans('adminlte.instrument.broken'),
            self::STATUS_IN_USE => trans('adminlte.instrument.in_use'),
            self::STATUS_NOT_RETURNED => trans('adminlte.instrument.not_returned'),
            self::STATUS_AVAILABLE => trans('adminlte.instrument.available'),
            self::STATUS_REPAIRED => trans('adminlte.instrument.repaired'),
        ];
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_UNAVAILABLE => '<span class="badge badge-warning">' . __('adminlte.instrument.unavailable') . '</span>',
            self::STATUS_BROKEN => '<span class="badge badge-danger">' . __('adminlte.instrument.broken') . '</span>',
            self::STATUS_IN_USE => '<span class="badge badge-secondary">' . __('adminlte.instrument.in_use') . '</span>',
            self::STATUS_NOT_RETURNED => '<span class="badge badge-dark">' . __('adminlte.instrument.not_returned') . '</span>',
            self::STATUS_AVAILABLE => '<span class="badge badge-success">' . __('adminlte.instrument.available') . '</span>',
            self::STATUS_REPAIRED => '<span class="badge badge-primary">' . __('adminlte.instrument.repaired') . '</span>',
            default => '<span class="badge badge-warning">Default</span>',
        };
    }

    public function statusName(): string
    {
        return self::statusList()[$this->type];
    }

    public function lend(?string $notes = null): void
    {
        $this->status = self::STATUS_IN_USE;
        if ($notes) {
            $this->notes = $notes;
        }
    }

    public function return(int $status, ?string $notes = null): void
    {
        $this->status = $status;
        if ($notes) {
            $this->notes = $notes;
        }
    }

    public function unavailable(?string $notes = null): void
    {
        $this->status = self::STATUS_UNAVAILABLE;
        if ($notes) {
            $this->notes = $notes;
        }
    }


    ########################################### Relations

    public function departmentInstrumentType(): BelongsTo|DepartmentInstrumentType
    {
        return $this->belongsTo(DepartmentInstrumentType::class, 'instrument_type_id', 'id');
    }

    public function createdBy(): BelongsTo|User
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo|User
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    ###########################################
}
