<?php

namespace App\Http\Requests\Admin\Users;

use App\Models\User\Profile;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property int $status
 * @property string $first_name
 * @property string $last_name
 * @property Carbon $birth_date
 * @property int $gender
 * @property string $address
 * @property \Illuminate\Http\UploadedFile $avatar
 */
class CreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => ['required', 'string', Rule::in(array_keys(User::rolesList()))],
            'password' => 'required|string',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date_format:Y-m-d',
            'gender' => ['numeric', Rule::in(array_keys(Profile::gendersList()))],
            'address' => 'nullable|string',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
