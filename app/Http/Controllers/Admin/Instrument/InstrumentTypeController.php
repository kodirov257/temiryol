<?php

namespace App\Http\Controllers\Admin\Instrument;

use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Instrument\InstrumentTypes\CreateRequest;
use App\Http\Requests\Admin\Instrument\InstrumentTypes\UpdateRequest;
use App\Models\Department;
use App\Models\Instrument\InstrumentType;
use App\Services\Manage\DepartmentService;
use App\Services\Manage\Instrument\InstrumentTypeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstrumentTypeController extends Controller
{
    private InstrumentTypeService $service;

    public function __construct(InstrumentTypeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $query = InstrumentType::with(['department'])->orderByDesc('updated_at');

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

            $defaultDepartment = Department::where('id', $value)->get()->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix(), 'id')->toArray();
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
                ->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix(), 'id')->toArray();

        return view('admin.instruments.create', compact('defaultDepartment'));
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        try {
            $department = $this->service->create($request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.instrument-types.show', $department);
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(InstrumentType $instrumentType): View
    {
        return view('admin.instruments.show', compact('instrumentType'));
    }

    public function edit(InstrumentType $instrument): View
    {
        $defaultDepartment = $instrument->department()->get()->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix(), 'id')->toArray();
        return view('admin.instruments.edit', compact('instrument', 'defaultDepartment'));
    }

    public function update(UpdateRequest $request, InstrumentType $instrument): RedirectResponse
    {
        try {
            $this->service->update($instrument->id, $request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.instrument-types.show', $instrument);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function removePhoto(InstrumentType $instrument): JsonResponse
    {
        if ($this->service->removePhoto($instrument->id)) {
            return response()->json('The photo is successfully deleted!');
        }
        return response()->json('The photo is not deleted!', 400);
    }

    public function destroy(InstrumentType $instrument): RedirectResponse
    {
        if ($instrument->created_by !== Auth::user()->id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard.instrument-types.index');
        }

        try {
            $instrument->delete();

            session()->flash('message', 'запись обновлён ');

            return redirect()->route('dashboard.instrument-types.index');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
