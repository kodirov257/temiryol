<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Organizations\CreateRequest;
use App\Http\Requests\Admin\Organizations\UpdateRequest;
use App\Models\Organization;
use App\Models\Region;
use App\Services\Manage\DepartmentService;
use App\Services\Manage\OrganizationService;
use App\Services\Manage\RegionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    private OrganizationService $service;

    public function __construct(OrganizationService $service)
    {
        $this->service = $service;
    }

    public function index(): View
    {
        $organizations = OrganizationService::getOrganizationsWithBranches();

        return view('admin.organizations.index', compact('organizations'));
    }

    public function create(Request $request): View
    {
        $parent = null;

        if ($request->get('parent')) {
            $parent = Organization::findOrFail($request->get('parent'));
        }

        return view('admin.organizations.create', compact('parent'));
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $organization = $this->service->create($request);
        session()->flash('message', 'запись обновлён ');
        return redirect()->route('dashboard.organizations.show', $organization);
    }

    public function show(Organization $organization): View
    {
        $branches = OrganizationService::getOrganizationsWithBranches($organization, false);
        $departments = DepartmentService::getDepartmentsWithDescendants();

        return view('admin.organizations.show', compact('organization', 'branches', 'departments'));
    }

    public function edit(Organization $organization): View
    {
        $defaultRegion = $organization->region()->get()->pluck('place', 'id')->toArray();

        return view('admin.organizations.edit', compact('organization', 'defaultRegion'));
    }

    public function update(UpdateRequest $request, Organization $organization): RedirectResponse
    {
        $this->service->update($organization->id, $request);
        session()->flash('message', 'запись обновлён ');
        return redirect()->route('dashboard.organizations.show', $organization);
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        $parentId = $organization->parent_id;
        if ($organization->created_by !== Auth::user()->id && !Auth::user()->isAdmin()) {
            if ($parentId) {
                return redirect()->route('dashboard.organizations.show', $parentId);
            }

            return redirect()->route('dashboard.organizations.index');
        }

        try {
            $organization->delete();

            session()->flash('message', 'запись обновлён ');

            if ($parentId) {
                return redirect()->route('dashboard.organizations.show', $parentId);
            }

            return redirect()->route('dashboard.organizations.index');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
