<?php

namespace App\Http\Requests\Admin\Departments;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property int $organization_id
 * @property int $parent
 * @property string $slug
 */
class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_uz' => 'required|string|max:255|unique:department_departments,name_uz,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_uz_cy' => 'required|string|max:255|unique:department_departments,name_uz_cy,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_ru' => 'required|string|max:255|unique:department_departments,name_ru,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_en' => 'required|string|max:255|unique:department_departments,name_en,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'organization_id' => 'required|int|min:1|exists:organizations,id',
            'parent' => 'nullable|int|exists:department_departments,id',
            'slug' => 'nullable|string|max:255|unique:department_departments,slug,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
        ];
    }
}
