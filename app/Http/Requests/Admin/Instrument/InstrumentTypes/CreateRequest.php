<?php

namespace App\Http\Requests\Admin\Instrument\InstrumentTypes;

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
 * @property UploadedFile $photo
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
            'name_uz' => 'required|string|max:255|unique:instrument_types,name_uz,NULL,id',
            'name_uz_cy' => 'required|string|max:255|unique:instrument_types,name_uz_cy,NULL,id',
            'name_ru' => 'required|string|max:255|unique:instrument_types,name_ru,NULL,id',
            'name_en' => 'required|string|max:255|unique:instrument_types,name_en,NULL,id',
            'description_uz' => 'required|string',
            'description_uz_cy' => 'required|string',
            'description_ru' => 'required|string',
            'description_en' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'slug' => 'nullable|string|max:255|unique:instrument_types,slug,NULL,id',
        ];
    }
}
