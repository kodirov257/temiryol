<?php

namespace App\Http\Resources\Search;

use App\Models\User\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
 */
class UserSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name . '; ' . $this->profile->fullName,
        ];
    }
}
