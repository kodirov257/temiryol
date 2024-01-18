<?php

namespace App\Http\Requests\Admin\Instrument\Operations;

use App\Models\Instrument\Instrument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $instrument_status
 * @property string $notes
 */
class CloseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instrument_status' => ['required', 'int', Rule::in(array_keys(Instrument::statusList()))],
            'notes' => 'nullable|string',
        ];
    }
}
