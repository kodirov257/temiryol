<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Departments\CreateRequest;
use App\Http\Requests\Admin\Departments\UpdateRequest;
use App\Models\Department;
use App\Models\Organization;
use App\Models\User\User;
use App\Services\Manage\DepartmentService;
use App\Services\Manage\OrganizationService;
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
            // $organization = Organization::findOrFail($value);
            // $organizationIds = [];
            // OrganizationService::getDescendantIds($organization, $organizationIds);
            // $query->whereIn('organization_id', $organizationIds);
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
        try {
            $department = $this->service->create($request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.departments.show', $department);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Department $department): View
    {
        $descendantIds = [];
        $childDepartments = DepartmentService::getDepartmentsWithDescendants($department, false);
        DepartmentService::getDescendantIds($department, $descendantIds);
        $employees = User::leftJoin('profiles as p', 'users.id', '=', 'p.user_id')
            ->whereIn('p.department_id', $descendantIds)->get();

        return view('admin.departments.show', compact('department', 'childDepartments', 'employees'));
    }

    public function edit(Department $department): View
    {
        $organizationList = OrganizationService::getOrganizationList();
        $departmentList = DepartmentService::getDepartmentList($department->id);

        return view('admin.departments.edit', compact('department', 'organizationList', 'departmentList'));
    }

    public function update(UpdateRequest $request, Department $department): RedirectResponse
    {
        try {
            $this->service->update($department->id, $request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.departments.show', $department);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addWorkerForm(Department $department): View
    {
        return view('admin.departments.add_worker', compact('department'));
    }

    public function addWorker(Request $request, Department $department): RedirectResponse
    {
        try {
            $request->validate([
                'worker_id' => 'required|int|min:1|exists:users,id',
            ]);

            if (!$this->service->addWorker($department->id, $request->worker_id)) {
                throw new \RuntimeException(__('adminlte.department.employee_not_added'));
            }
            session()->flash('message', __('adminlte.department.employee_added'));
            return redirect()->route('dashboard.departments.show', $department);
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function removeWorker(Department $department, User $worker): RedirectResponse
    {
        try {
            if ($this->service->removeWorker($department->id, $worker->id)) {
                throw new \RuntimeException(__('adminlte.department.employee_not_removed'));
            }
            session()->flash('message', __('adminlte.department.employee_removed'));
            return redirect()->route('dashboard.departments.show', $department);
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
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
