<?php

namespace App\Models;

use App\Helpers\LanguageHelper;
use App\Models\User\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
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
 * @property int $organization_id
 * @property int $parent_id
 * @property string $slug
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Organization $organization
 * @property Department $parent
 * @property Department[] $children
 * @property User $createdBy
 * @property User $updatedBy
 *
 * @property string $name
 *
 * @mixin Eloquent
 */
class Department extends Model
{
    use /*HasFactory, */Sluggable;

    protected $table = 'departments';

    public int $depth;

    protected $fillable = [
        'name_uz', 'name_uz_cy', 'name_ru', 'name_en', 'organization_id', 'parent_id', 'slug',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name_en',
            ],
        ];
    }


    ########################################### Mutators

    public function getNameAttribute(): string
    {
        return htmlspecialchars_decode(LanguageHelper::getName($this));
    }

    ###########################################


    ########################################### Relations

    public function organization(): BelongsTo|Region
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function parent(): BelongsTo|self
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
