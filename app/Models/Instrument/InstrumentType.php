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
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property string $description_uz
 * @property string $description_uz_cy
 * @property string $description_ru
 * @property string $description_en
 * @property string $photo
 * @property int $department_id
 * @property string $slug
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property DepartmentInstrumentType[] $instrumentDepartments
 * @property Department[] $departments
 * @property User $createdBy
 * @property User $updatedBy
 *
 * @property string $name
 * @property string $description
 * @property string $photoThumbnail
 * @property string $photoOriginal
 *
 * @mixin Eloquent
 */
class InstrumentType extends BaseModel
{
    use /*HasFactory, */Sluggable;

    protected $table = 'instrument_types';

    public int $depth;

    protected $fillable = [
        'name_uz', 'name_uz_cy', 'name_ru', 'name_en', 'description_uz', 'description_uz_cy', 'description_ru',
        'description_en', 'department_id', 'slug',
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

    public function getDescriptionAttribute(): string
    {
        return LanguageHelper::getDescription($this);
    }

    public function getPhotoThumbnailAttribute(): string
    {
        return '/storage/files/' . ImageHelper::FOLDER_INSTRUMENTS . '/' . $this->id . '/' . ImageHelper::TYPE_THUMBNAIL . '/' . $this->photo;
    }

    public function getPhotoOriginalAttribute(): string
    {
        return '/storage/files/' . ImageHelper::FOLDER_INSTRUMENTS . '/' . $this->id . '/' . ImageHelper::TYPE_ORIGINAL . '/' . $this->photo;
    }

    ###########################################


    ########################################### Relations

    /**
     * @return BelongsToMany|DepartmentInstrumentType[]
     */
    public function instrumentDepartments(): HasMany|array
    {
        return $this->hasMany(DepartmentInstrumentType::class, 'type_id', 'id');
    }

    /**
     * @return BelongsToMany|Department[]
     */
    public function departments(): BelongsToMany|array
    {
        return $this->belongsToMany(Department::class, 'department_instrument_type', 'type_id', 'department_id');
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
