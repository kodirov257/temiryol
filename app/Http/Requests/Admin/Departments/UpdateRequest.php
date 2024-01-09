<?php

namespace App\Http\Requests\Admin\Departments;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property int $organization_id
 * @property int $parent_id
 * @property string $slug
 *
 * @property Department $department
 */
class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_uz' => 'required|string|max:255|unique:departments,name_uz,' . $this->department->id . ',id,parent_id,' . ($this->department->parent_id ?: 'NULL'),
            'name_uz_cy' => 'required|string|max:255|unique:departments,name_uz_cy,' . $this->department->id . ',id,parent_id,' . ($this->department->parent_id ?: 'NULL'),
            'name_ru' => 'required|string|max:255|unique:departments,name_ru,' . $this->department->id . ',id,parent_id,' . ($this->department->parent_id ?: 'NULL'),
            'name_en' => 'required|string|max:255|unique:departments,name_en,' . $this->department->id . ',id,parent_id,' . ($this->department->parent_id ?: 'NULL'),
            'organization_id' => 'required|int|min:1|exists:departments,id',
            'parent_id' => 'nullable|int|exists:departments,id',
            'slug' => 'nullable|string|max:255|unique:departments,slug,' . $this->department->id . ',id,parent_id,' . ($this->department->parent_id ?: 'NULL'),
        ];
    }
}
