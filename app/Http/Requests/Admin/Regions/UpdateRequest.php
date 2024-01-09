<?php

namespace App\Http\Requests\Admin\Regions;

use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property string $type
 * @property string $slug
 *
 * @property Region $region
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
            'name_uz' => 'required|string|max:255|unique:regions,name_uz,' . $this->region->id . ',id,parent_id,' . ($this->region->parent_id ?: 'NULL'),
            'name_uz_cy' => 'required|string|max:255|unique:regions,name_uz_cy,' . $this->region->id . ',id,parent_id,' . ($this->region->parent_id ?: 'NULL'),
            'name_ru' => 'required|string|max:255|unique:regions,name_ru,' . $this->region->id . ',id,parent_id,' . ($this->region->parent_id ?: 'NULL'),
            'name_en' => 'required|string|max:255|unique:regions,name_en,' . $this->region->id . ',id,parent_id,' . ($this->region->parent_id ?: 'NULL'),
//            'slug' => ['nullable', 'string', 'max:255', Rule::unique('regions')->ignore($this->region->id)],
            'slug' => 'nullable|string|max:255|unique:regions,slug,' . $this->region->id . ',id,parent_id,' . ($this->region->parent_id ?: 'NULL'),
        ];
    }
}
