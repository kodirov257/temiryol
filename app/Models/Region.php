<?php

namespace App\Models;

use App\Helpers\LanguageHelper;
use App\Models\User\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property int $parent_id
 * @property string $type
 * @property string $slug
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Region $parent
 * @property Region[] $children
 * @property Region[] $cities
 * @property Region[] $districts
 * @property Region $center
 * @property Region[] $towns
 * @property User $createdBy
 * @property User $updatedBy
 *
 * @property string $name
 *
 * @method Builder|self regions()
 *
 * @mixin Eloquent
 */
class Region extends BaseModel
{
    use Sluggable;

    public const REGION = 'region';
    public const CITY = 'city';
    public const DISTRICT = 'district';
    public const CENTER = 'center';
    public const TOWN = 'town';

    protected $table = 'regions';

    protected $fillable = ['name_uz', 'name_uz_cy', 'name_ru', 'name_en', 'parent_id', 'type', 'slug'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name_en',
            ],
        ];
    }

    public static function typeList(): array
    {
        return [
            self::REGION => trans('adminlte.region.region'),
            self::CITY => trans('adminlte.region.city'),
            self::DISTRICT => trans('adminlte.region.district'),
            self::CENTER => trans('adminlte.region.center'),
            self::TOWN => trans('adminlte.region.town'),
        ];
    }

    public function typeName(): string
    {
        return self::typeList()[$this->type];
    }

    public function getPath(): string
    {
        return ($this->parent ? $this->parent->getPath() . '/' : '') . $this->slug;
    }

    public function getAddress(): string
    {
        return ($this->parent ? $this->parent->getAddress() . ', ' : '') . $this->name;
    }

    public function getPlace(): string
    {
        return $this->name . ($this->parent ? ', ' . $this->parent->getPlace() : '');
    }


    ########################################### Mutators

    public function getNameAttribute(): string
    {
        return htmlspecialchars_decode(LanguageHelper::getName($this));
    }

    public function getPlaceAttribute(): string
    {
        return $this->getPlace();
    }

    ###########################################


    ########################################### Scopes

    public function scopeRegions(Builder $query): Builder|self
    {
        return $query->where('parent_id', null);
    }

    ###########################################


    ########################################### Relations

    /**
     * @return BelongsTo|self[]
     */
    public function parent(): BelongsTo|array
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

    /**
     * @return HasMany|self[]
     */
    public function cities(): HasMany|array
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->where('type', self::CITY);
    }

    /**
     * @return HasMany|self[]
     */
    public function districts(): HasMany|array
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->where('type', self::DISTRICT);
    }

    /**
     * @return HasOne|self
     */
    public function center(): HasOne|self
    {
        return $this->hasOne(self::class, 'parent_id', 'id')
            ->where('type', self::CENTER);
    }

    /**
     * @return HasMany|self[]
     */
    public function towns(): HasMany|array
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->where('type', self::TOWN);
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
