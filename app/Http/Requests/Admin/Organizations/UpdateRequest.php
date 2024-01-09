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
 *
 * @property Organization $organization
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
            'name_uz' => 'required|string|max:255|unique:organizations,name_uz,' . $this->organization->id . ',id,parent_id,' . ($this->organization->parent_id ?: 'NULL'),
            'name_uz_cy' => 'required|string|max:255|unique:organizations,name_uz_cy,' . $this->organization->id . ',id,parent_id,' . ($this->organization->parent_id ?: 'NULL'),
            'name_ru' => 'required|string|max:255|unique:organizations,name_ru,' . $this->organization->id . ',id,parent_id,' . ($this->organization->parent_id ?: 'NULL'),
            'name_en' => 'required|string|max:255|unique:organizations,name_en,' . $this->organization->id . ',id,parent_id,' . ($this->organization->parent_id ?: 'NULL'),
            'region_id' => 'required|int|min:1|exists:regions,id',
            'type' => ['required', 'string', Rule::in(array_keys(Organization::typeList()))],
//            'slug' => ['nullable', 'string', 'max:255', Rule::unique('organizations')->ignore($this->region->id)],
            'slug' => 'nullable|string|max:255|unique:organizations,slug,' . $this->organization->id . ',id,parent_id,' . ($this->organization->parent_id ?: 'NULL'),
        ];
    }
}
