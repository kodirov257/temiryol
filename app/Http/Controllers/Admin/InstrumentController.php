<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Instrument\Instrument;
use App\Models\Instrument\InstrumentType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstrumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Instrument::select(['instrument_instruments.*', 'pit.type_id', 'pit.department_id', 'pit.quantity'])
            ->leftJoin('department_instrument_types as pit', 'instrument_instruments.instrument_type_id', '=', 'pit.id')
            ->orderByDesc('updated_at');

        if (!empty($value = $request->get('serial'))) {
            $query->where('serial', 'ilike', '%' . $value . '%');
        }

        if (!empty($value = $request->get('type'))) {
            $query->where('pit.type_id', $value);
        }

        $defaultDepartment = [];
        if (!empty($value = $request->get('department'))) {
            $query->where('pit.department_id', $value);

            $defaultDepartment = Department::where('id', $value)->get()
                ->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix(), 'id')->toArray();
        }

        $instruments = $query->paginate(20)
            ->appends('serial', $request->get('serial'))
            ->appends('type', $request->get('type'))
            ->appends('department', $request->get('department'));

        $types = InstrumentType::orderBy('name_' . LanguageHelper::getCurrentLanguagePrefix())
            ->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix(), 'id')->toArray();

        return view('admin.instruments.index', compact('instruments', 'types', 'defaultDepartment'));
    }
}
