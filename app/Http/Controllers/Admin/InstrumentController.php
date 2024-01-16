<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Instruments\CreateRequest;
use App\Http\Requests\Admin\Instruments\UpdateRequest;
use App\Models\Department;
use App\Models\Instrument;
use App\Services\Manage\DepartmentService;
use App\Services\Manage\InstrumentService;
use App\Services\Manage\OrganizationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstrumentController extends Controller
{
    private InstrumentService $service;

    public function __construct(InstrumentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $query = Instrument::with(['department'])->orderByDesc('updated_at');

        if (!empty($value = $request->get('name'))) {
            $query->where(function (Builder $query) use ($value) {
                $query->where('name_uz', 'ilike', '%' . $value . '%')
                    ->orWhere('name_uz_cy', 'ilike', '%' . $value . '%')
                    ->orWhere('name_ru', 'ilike', '%' . $value . '%')
                    ->orWhere('name_en', 'ilike', '%' . $value . '%');
            });
        }

        $defaultDepartment = [];
        if (!empty($value = $request->get('department_id'))) {
            $department = Department::findOrFail($value);
            $departmentIds = [];
            DepartmentService::getDescendantIds($department, $departmentIds);
            $query->whereIn('department_id', $departmentIds);

            $defaultDepartment = Department::where('id', $value)->get()->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix())->toArray();
        }

        $instruments = $query->paginate(20)
            ->appends('name', $request->get('name'))
            ->appends('department_id', $request->get('department_id'));

        return view('admin.instruments.index', compact('instruments', 'defaultDepartment'));
    }

    public function create(Request $request): View
    {
        $defaultDepartment = $request->has('department_id') ? [] :
            Department::where('id', $request->get('department_id'))->get()
                ->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix())->toArray();

        return view('admin.instruments.create', compact('defaultDepartment'));
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        try {
            $department = $this->service->create($request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.instruments.show', $department);
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Instrument $instrument): View
    {
        return view('admin.instruments.show', compact('instrument'));
    }

    public function edit(Instrument $instrument): View
    {
        $defaultDepartment = $instrument->department()->get()->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix())->toArray();
        return view('admin.instruments.edit', compact('instrument', 'defaultDepartment'));
    }

    public function update(UpdateRequest $request, Instrument $instrument): RedirectResponse
    {
        try {
            $this->service->update($instrument->id, $request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.instruments.show', $instrument);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function removePhoto(Instrument $instrument): JsonResponse
    {
        if ($this->service->removePhoto($instrument->id)) {
            return response()->json('The photo is successfully deleted!');
        }
        return response()->json('The photo is not deleted!', 400);
    }

    public function destroy(Instrument $instrument): RedirectResponse
    {
        if ($instrument->created_by !== Auth::user()->id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard.instruments.index');
        }

        try {
            $instrument->delete();

            session()->flash('message', 'запись обновлён ');

            return redirect()->route('dashboard.instruments.index');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
