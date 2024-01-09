<?php

namespace App\Models;

use App\Helpers\LanguageHelper;
use App\Models\User\User;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property int $region_id
 * @property int $parent_id
 * @property string $type
 * @property string $slug
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Region $region
 * @property Organization $parent
 * @property Organization[] $children
 * @property User $createdBy
 * @property User $updatedBy
 *
 * @property string $name
 *
 * @mixin Eloquent
 */
class Organization extends Model
{
//    use HasFactory;

    public const PUBLIC_COMPANY = 'public_company';
    public const SUBSIDIARY = 'subsidiary';
    public const BRANCH = 'branch';

    protected $table = 'organizations';

    public int $depth;

    protected $fillable = [
        'name_uz', 'name_uz_cy', 'name_ru', 'name_en', 'region_id', 'parent_id', 'type', 'slug',
    ];

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
            self::PUBLIC_COMPANY => trans('adminlte.organization.public_company'),
            self::SUBSIDIARY => trans('adminlte.organization.subsidiary'),
            self::BRANCH => trans('adminlte.organization.branch'),
        ];
    }

    public function typeName(): string
    {
        return self::typeList()[$this->type];
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


    ########################################### Relations

    public function parent(): BelongsTo|Organization
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

    public function region(): BelongsTo|Region
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
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
