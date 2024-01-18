<?php

namespace App\Http\Requests\Admin\Instrument\Operations;

use App\Models\Instrument\Operation;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $borrower_id
 * @property Carbon $deadline_date
 * @property Carbon $deadline_time
 * @property string $notes
 *
 * @property Operation $operation
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
            'borrower_id' => 'required|int|min:1|exists:users,id',
//            'type' => ['required', 'int', Rule::in(array_keys(Operation::typeList()))],
//            'status' => ['required', 'int', Rule::in(array_keys(Operation::statusList()))],
            'deadline_date' => 'required|date_format:Y-m-d',
            'deadline_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ];
    }
}
