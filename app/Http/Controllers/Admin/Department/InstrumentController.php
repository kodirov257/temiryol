<?php

namespace App\Http\Controllers\Admin\Department;

use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Department\Instruments\CreateRequest;
use App\Models\Department;
use App\Models\Instrument\InstrumentType;
use App\Services\Manage\Department\InstrumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InstrumentController extends Controller
{
    private InstrumentService $service;

    public function __construct(InstrumentService $service)
    {
        $this->service = $service;
    }

    public function create(Department $department): View
    {
        $types = InstrumentType::orderBy('name_' . LanguageHelper::getCurrentLanguagePrefix())
            ->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix(), 'id')->toArray();

        return view('admin.department.instruments.create', compact('department', 'types'));
    }

    public function store(CreateRequest $request, Department $department): RedirectResponse
    {
        try {
            $instrument = $this->service->create($department->id, $request->type_id, $request->serial, $request->status, $request->notes);
            session()->flash('message', 'запись обновлён');
            return redirect()->route('dashboard.department-instrument-types.instruments.show', [
                'departmentInstrumentType' => $instrument->departmentInstrumentType,
                'instrument' => $instrument,
            ]);
        } catch (ValidationException $e) {
            return redirect()->back($e->status)->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
