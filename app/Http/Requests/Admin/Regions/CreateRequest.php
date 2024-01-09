<?php

namespace App\Http\Requests\Admin\Regions;

use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
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
            'name_uz' => 'required|string|max:255|unique:regions,name_uz,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_uz_cy' => 'required|string|max:255|unique:regions,name_uz_cy,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_ru' => 'required|string|max:255|unique:regions,name_ru,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'name_en' => 'required|string|max:255|unique:regions,name_en,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
            'parent' => 'nullable|exists:regions,id',
            'type' => ['required_with_all:parent', 'string', Rule::in(array_keys(Region::typeList()))],
            'slug' => 'nullable|string|max:255|unique:regions,slug,NULL,id,parent_id,' . ($this['parent'] ?: 'NULL'),
        ];
    }
}
