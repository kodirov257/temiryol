<?php

namespace App\Http\Controllers\Admin\Instrument;

use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Instrument\Instruments\CreateRequest;
use App\Models\Department;
use App\Models\Instrument\DepartmentInstrumentType;
use App\Models\Instrument\Instrument;
use App\Models\Instrument\InstrumentType;
use App\Services\Manage\Instrument\InstrumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InstrumentController extends Controller
{
    private InstrumentService $service;

    public function __construct(InstrumentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, DepartmentInstrumentType $departmentInstrumentType): View
    {
        $query = Instrument::with(['departmentInstrumentType'])
            ->where('instrument_type_id', $departmentInstrumentType->id)
            ->orderByDesc('updated_at');

        if (!empty($value = $request->get('serial'))) {
            $query->where('serial', 'ilike', '%' . $value . '%');
        }

        $instruments = $query->paginate(20)
            ->appends('serial', $request->get('serial'));

        return view('admin.instrument.instruments.index', compact('instruments', 'departmentInstrumentType'));
    }
    public function indexAll(Request $request): View
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

        return view('admin.instrument.instruments.index-all', compact('instruments', 'types', 'defaultDepartment'));
    }

    public function create(DepartmentInstrumentType $departmentInstrumentType): View
    {
        return view('admin.instrument.instruments.create', compact('departmentInstrumentType'));
    }

    public function store(CreateRequest $request, DepartmentInstrumentType $departmentInstrumentType): RedirectResponse
    {
        try {
            $instrument = $this->service->create($departmentInstrumentType->id, $request->serial, $request->status, $request->notes);
            session()->flash('message', 'запись обновлён');
            return redirect()->route('dashboard.department-instrument-types.instruments.show', ['departmentInstrumentType' => $departmentInstrumentType, 'instrument' => $instrument]);
        } catch (ValidationException $e) {
            return redirect()->back($e->status)->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(DepartmentInstrumentType $departmentInstrumentType, Instrument $instrument): View
    {
        return view('admin.instrument.instruments.show', compact('departmentInstrumentType', 'instrument'));
    }

    public function edit(DepartmentInstrumentType $departmentInstrumentType, Instrument $instrument): View
    {
        return view('admin.instrument.instruments.edit', compact('departmentInstrumentType', 'instrument'));
    }

    public function update(Request $request, DepartmentInstrumentType $departmentInstrumentType, Instrument $instrument): RedirectResponse
    {
        try {
            $request->validate([
                'serial' => 'required|string|max:255|unique:instrument_instruments,serial,' . $instrument->id . ',id,instrument_type_id,' . ($departmentInstrumentType->id ?: 'NULL'),
                'status' => ['required', 'int', Rule::in(array_keys(Instrument::statusList()))],
                'notes' => 'nullable|string',
            ]);

            $this->service->update($instrument->id, $departmentInstrumentType->id, $request['serial'], $request['status'], $request['notes']);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.department-instrument-types.instruments.show', ['departmentInstrumentType' => $departmentInstrumentType, 'instrument' => $instrument]);
        } catch (ValidationException $e) {
            return redirect()->back($e->status)->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroyForm(DepartmentInstrumentType $departmentInstrumentType, Instrument $instrument): View
    {
        return view('admin.instrument.instruments.destroy', compact('departmentInstrumentType', 'instrument'));
    }

    public function destroy(Request $request, DepartmentInstrumentType $departmentInstrumentType, Instrument $instrument): RedirectResponse
    {
        if ($instrument->created_by !== Auth::user()->id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard.departments.show', $departmentInstrumentType->department);
        }

        try {
            $request->validate([
                'notes' => 'nullable|string',
            ]);

            $this->service->remove($instrument->id, $departmentInstrumentType->id);

            session()->flash('message', 'запись обновлён');

            return redirect()->route('dashboard.departments.show', $departmentInstrumentType->department);
        } catch (ValidationException $e) {
            return redirect()->back($e->status)->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
