<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Departments\CreateRequest;
use App\Http\Requests\Admin\Departments\UpdateRequest;
use App\Models\Department;
use App\Services\Manage\DepartmentService;
use App\Services\Manage\OrganizationService;
use App\Services\Manage\RegionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    private DepartmentService $service;

    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $query = Department::with(['organization'])->where('parent_id', null)->orderByDesc('updated_at');

        if (!empty($value = $request->get('name'))) {
            $query->where(function (Builder $query) use ($value) {
                $query->where('name_uz', 'ilike', '%' . $value . '%')
                    ->orWhere('name_uz_cy', 'ilike', '%' . $value . '%')
                    ->orWhere('name_ru', 'ilike', '%' . $value . '%')
                    ->orWhere('name_en', 'ilike', '%' . $value . '%');
            });
        }

        if (!empty($value = $request->get('organization_id'))) {
            $query->where('organization_id', $value);
        }

        $departments = $query->paginate(20)
            ->appends('name', $request->get('name'))
            ->appends('organization_id', $request->get('organization_id'));

        $organizations = OrganizationService::getOrganizationList();

        return view('admin.departments.index', compact('departments', 'organizations'));
    }

    public function create(Request $request): View
    {
        $organizationList = $request->get('organization_id') ? [] : OrganizationService::getOrganizationList();

        $parent = null;

        if ($request->get('parent')) {
            $parent = Department::findOrFail($request->get('parent'));
        }

        return view('admin.departments.create', compact('parent', 'organizationList'));
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $department = $this->service->create($request);
        session()->flash('message', 'запись обновлён ');
        return redirect()->route('dashboard.departments.show', $department);
    }

    public function show(Department $department): View
    {
        $childDepartments = DepartmentService::getDepartmentsWithDescendants($department, false);
        return view('admin.departments.show', compact('department', 'childDepartments'));
    }

    public function edit(Department $department): View
    {
        $organizationList = OrganizationService::getOrganizationList();
        $departmentList = DepartmentService::getDepartmentList($department->id);

        return view('admin.departments.edit', compact('department', 'organizationList', 'departmentList'));
    }

    public function update(UpdateRequest $request, Department $department): RedirectResponse
    {
        $this->service->update($department->id, $request);
        session()->flash('message', 'запись обновлён ');
        return redirect()->route('dashboard.departments.show', $department);
    }

    public function destroy(Department $department): RedirectResponse
    {
        $parentId = $department->parent_id;
        if ($department->created_by !== Auth::user()->id && !Auth::user()->isAdmin()) {
            if ($parentId) {
                return redirect()->route('dashboard.departments.show', $parentId);
            }

            return redirect()->route('dashboard.departments.index');
        }

        try {
            $department->delete();

            session()->flash('message', 'запись обновлён ');

            if ($parentId) {
                return redirect()->route('dashboard.departments.show', $parentId);
            }

            return redirect()->route('dashboard.departments.index');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
