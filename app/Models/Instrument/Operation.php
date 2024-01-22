<?php

namespace App\Models\Instrument;

use App\Models\BaseModel;
use App\Models\Department;
use App\Models\User\User;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $borrower_id
 * @property int $instrument_id
 * @property int $department_id
 * @property int $instrument_type_id
 * @property string $serial
 * @property int $type
 * @property int $status
 * @property int $instrument_status
 * @property Carbon $deadline
 * @property string $unique_id
 * @property int $parent_id
 * @property string $notes
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $borrower
 * @property Instrument $instrument
 * @property Department $department
 * @property InstrumentType $instrumentType
 * @property Operation $parent
 * @property Operation[] $children
 * @property User $createdBy
 * @property User $updatedBy
 *
 * @method Builder|self active()
 *
 * @mixin Eloquent
 */
class Operation extends BaseModel
{
    public const TYPE_RENT = 1;
    public const TYPE_FIX = 2;

    public const STATUS_ACTIVE = 1;
    public const STATUS_PROLONGED = 2;
    public const STATUS_EXPIRED = 5;
    public const STATUS_CLOSED = 9;

    protected $table = 'instrument_operations';

    protected $fillable = [
        'borrower_id', 'instrument_id', 'department_id', 'instrument_type_id', 'serial', 'parent_id', 'unique_id',
        'type', 'status', 'instrument_status', 'deadline', 'notes',
    ];

    protected $hidden = ['unique_id'];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public int $depth;

    public static function typeList(): array
    {
        return [
            self::TYPE_RENT => trans('adminlte.operation.rent'),
            self::TYPE_FIX => trans('adminlte.operation.fixing'),
        ];
    }

    public function typeName(): string
    {
        return self::typeList()[$this->type];
    }

    public static function statusList(): array
    {
        return [
            self::STATUS_ACTIVE => trans('adminlte.active'),
            self::STATUS_PROLONGED => trans('adminlte.prolonged'),
            self::STATUS_EXPIRED => trans('adminlte.expired'),
            self::STATUS_CLOSED => trans('adminlte.closed'),
        ];
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => '<span class="badge badge-success">' . __('adminlte.active') . '</span>',
            self::STATUS_PROLONGED => '<span class="badge badge-warning">' . __('adminlte.prolonged') . '</span>',
            self::STATUS_EXPIRED => '<span class="badge badge-danger">' . __('adminlte.expired') . '</span>',
            self::STATUS_CLOSED => '<span class="badge badge-primary">' . __('adminlte.closed') . '</span>',
            default => '<span class="badge badge-danger">Default</span>',
        };
    }

    public function statusName(): string
    {
        return self::statusList()[$this->status];
    }

    public function isProlonged(): bool
    {
        return $this->status === self::STATUS_PROLONGED;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_PROLONGED]);
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }


    ########################################### Scopes

    public function scopeActive(Builder $query): Builder|self
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_PROLONGED]);
    }

    ###########################################


    ########################################### Relations

    public function borrower(): BelongsTo|User
    {
        return $this->belongsTo(User::class, 'borrower_id', 'id');
    }

    public function instrument(): BelongsTo|Instrument
    {
        return $this->belongsTo(Instrument::class, 'instrument_id', 'id');
    }

    public function department(): BelongsTo|Department
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function instrumentType(): BelongsTo|InstrumentType
    {
        return $this->belongsTo(InstrumentType::class, 'instrument_type_id', 'id');
    }

    public function parent(): BelongsTo|Operation
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    /**
     * @return HasMany|self[]
     */
    public function children(): HasMany|array
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
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
