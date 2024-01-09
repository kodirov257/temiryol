<?php

namespace App\Http\Requests\Admin\Organizations;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property int $region_id
 * @property int $parent
 * @property string $type
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
            'name_uz' => 'required|string|max:255|unique:organizations,name_uz,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_uz_cy' => 'required|string|max:255|unique:organizations,name_uz_cy,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_ru' => 'required|string|max:255|unique:organizations,name_ru,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_en' => 'required|string|max:255|unique:organizations,name_en,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'region_id' => 'required|int|min:1|exists:regions,id',
            'parent' => 'nullable|int|exists:organizations,id',
            'type' => ['required', 'string', Rule::in(array_keys(Organization::typeList()))],
            'slug' => 'nullable|string|max:255|unique:organizations,slug,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
        ];
    }
}
