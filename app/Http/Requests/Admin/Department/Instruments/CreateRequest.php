<?php

namespace App\Http\Requests\Admin\Department\Instruments;

use App\Models\Instrument\Instrument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * @property int $type_id
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
            'type_id' => 'required|int|min:1|exists:instrument_types,id',
            'serial' => 'required|string|max:255|unique:instrument_instruments,serial,NULL,id,instrument_type_id,' . ($this['type_id'] ?: 'NULL'),
            'status' => ['required', 'int', Rule::in(array_keys(Instrument::statusList()))],
            'notes' => 'nullable|string',
        ];
    }
}
