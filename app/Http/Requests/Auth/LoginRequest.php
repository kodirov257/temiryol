<?php

namespace App\Http\Requests\Auth;

use App\Helpers\UserHelper;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\AuthenticationService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @property string $email_or_username
 * @property string $password
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if (UserHelper::isEmail($this->email_or_username)) {
            $rule = ['email_or_username' => 'required|string|email|max:100'];
        } else {
            $rule = ['email_or_username' => 'required|string|max:100'];
        }

        return array_merge($rule, [
            'password' => 'required|string|max:120',
        ]);
    }

    public function username(): string
    {
        return 'email_or_username';
    }
}
