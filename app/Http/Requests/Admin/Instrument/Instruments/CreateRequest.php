<?php

namespace App\Http\Requests\Admin\Instrument\Instruments;

use App\Models\Instrument\Instrument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * @property string $serial
 * @property int $status
 * @property string $notes
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
            'serial' => 'required|string|max:255|unique:instrument_instruments,serial,NULL,id,instrument_type_id,' . ($this['instrument_type_id'] ?: 'NULL'),
            'status' => ['required', 'int', Rule::in(array_keys(Instrument::statusList()))],
            'notes' => 'nullable|string',
        ];
    }
}
