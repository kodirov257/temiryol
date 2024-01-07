<?php

namespace App\Models\User;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $email_verify_token
 * @property boolean $email_verified
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $role
 * @property int $status
 * @property string $google2fa_secret
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Profile $profile
 *
 * @mixin Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public const STATUS_WAIT = 0;
    public const STATUS_ACTIVE = 5;
    public const STATUS_BLOCKED = 9;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';
    public const ROLE_ACCOUNTANT = 'accountant';
    public const ROLE_WORKER = 'worker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'status',
        'password',
        'email_verified',
        'email_verify_token',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verify_token',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

        static::saving(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAccountant(): bool
    {
        return $this->role === self::ROLE_ACCOUNTANT;
    }

    public function isWorker(): bool
    {
        return $this->role === self::ROLE_WORKER;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function hasVerifiedEmail(): bool
    {
        return parent::hasVerifiedEmail() && $this->email_verified;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'status' => self::STATUS_ACTIVE,
            'email_verify_token' => null,
            'email_verified_at' => $this->freshTimestamp(),
            'email_verified' => true,
        ])->save();
    }

    public function google2faSecret(): Attribute
    {
        return new Attribute(
            get: fn ($value) => decrypt($value),
            set: fn ($value) => encrypt($value),
        );
    }

    public static function rolesList(): array
    {
        return [
            self::ROLE_USER => trans('adminlte.user.role_user'),
            self::ROLE_ACCOUNTANT => trans('adminlte.user.role_accountant'),
            self::ROLE_WORKER => trans('adminlte.user.role_worker'),
            self::ROLE_ADMIN => trans('adminlte.user.role_administrator'),
        ];
    }

    public function roleName(): string
    {
        return self::rolesList()[$this->role];
    }

    public static function statusesList(): array
    {
        return [
            self::STATUS_WAIT => trans('adminlte.user.waiting'),
            self::STATUS_ACTIVE => trans('adminlte.user.active'),
            self::STATUS_BLOCKED => trans('adminlte.user.blocked'),
        ];
    }


    ########################################### Relations

    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne|Profile
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    ###########################################
}
