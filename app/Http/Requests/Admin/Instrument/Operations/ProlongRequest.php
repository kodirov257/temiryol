<?php

namespace App\Http\Requests\Admin\Instrument\Operations;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Carbon $deadline_date
 * @property Carbon $deadline_time
 * @property string $notes
 */
class ProlongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'deadline_date' => 'required|date_format:Y-m-d',
            'deadline_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ];
    }
}
