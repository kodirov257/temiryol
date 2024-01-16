<?php

namespace App\Http\Requests\Admin\Instruments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property string $description_uz
 * @property string $description_uz_cy
 * @property string $description_ru
 * @property string $description_en
 * @property int $quantity
 * @property int $weight
 * @property UploadedFile $photo
 * @property int $department_id
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
            'name_uz' => 'required|string|max:255|unique:instruments,name_uz,NULL,id,department_id,' . ($this['department_id'] ?: 'NULL'),
            'name_uz_cy' => 'required|string|max:255|unique:instruments,name_uz_cy,NULL,id,department_id,' . ($this['department_id'] ?: 'NULL'),
            'name_ru' => 'required|string|max:255|unique:instruments,name_ru,NULL,id,department_id,' . ($this['department_id'] ?: 'NULL'),
            'name_en' => 'required|string|max:255|unique:instruments,name_en,NULL,id,department_id,' . ($this['department_id'] ?: 'NULL'),
            'description_uz' => 'required|string',
            'description_uz_cy' => 'required|string',
            'description_ru' => 'required|string',
            'description_en' => 'required|string',
            'quantity' => 'required|int',
            'weight' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'department_id' => 'required|int|min:1|exists:departments,id',
            'slug' => 'nullable|string|max:255|unique:instruments,slug,NULL,id,department_id,' . ($this['department_id'] ?: 'NULL'),
        ];
    }
}
