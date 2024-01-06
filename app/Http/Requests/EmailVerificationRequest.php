<?php

namespace App\Http\Requests;

use App\Models\User\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest as BaseRequest;

class EmailVerificationRequest extends BaseRequest
{
    public User $user;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->user = User::find($this->route('id'))) {
            return false;
        }

        if (! hash_equals((string) $this->user->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (! hash_equals($this->user->email_verify_token, (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill(): void
    {
        if (! $this->user->hasVerifiedEmail()) {
            $this->user->markEmailAsVerified();

            event(new Verified($this->user));
        }
    }
}
