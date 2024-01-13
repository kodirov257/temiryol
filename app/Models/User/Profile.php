<?php

namespace App\Models\User;

use App\Helpers\ImageHelper;
use App\Models\Department;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property Carbon $birth_date
 * @property int $gender
 * @property string $address
 * @property string $fullName
 * @property string $avatar
 * @property string $department_id
 *
 * @property string $avatarThumbnail
 * @property string $avatarOriginal
 *
 * @property User $user
 * @property Department $department
 *
 * @mixin Eloquent
 */
class Profile extends Model
{
    use HasFactory;

    public const GENDER_EMPTY = 0;
    public const FEMALE = 1;
    public const MALE = 2;

    protected $table = 'profiles';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $fillable = ['user_id', 'first_name', 'last_name', 'middle_name', 'birth_date', 'gender', 'address', 'avatar'];

    protected $casts = ['birth_date' => 'datetime'];

    public static function gendersList(): array
    {
        return [
            self::GENDER_EMPTY => '',
            self::FEMALE => trans('adminlte.female'),
            self::MALE => trans('adminlte.male'),
        ];
    }

    public function genderName(): string
    {
        return self::gendersList()[$this->gender];
    }

    public function getFullNameAttribute(): string
    {
        return "$this->last_name $this->middle_name $this->first_name";
    }

    public function getAvatarThumbnailAttribute(): string
    {
        return '/storage/files/' . ImageHelper::FOLDER_PROFILES . '/' . $this->user_id . '/' . ImageHelper::TYPE_THUMBNAIL . '/' . $this->avatar;
    }

    public function getAvatarOriginalAttribute(): string
    {
        return '/storage/files/' . ImageHelper::FOLDER_PROFILES . '/' . $this->user_id . '/' . ImageHelper::TYPE_ORIGINAL . '/' . $this->avatar;
    }


    ########################################### Relations

    public function user(): BelongsTo|User
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function department(): BelongsTo|User
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    ###########################################
}
