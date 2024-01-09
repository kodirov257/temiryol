<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Regions\CreateRequest;
use App\Http\Requests\Admin\Regions\UpdateRequest;
use App\Models\Region;
use App\Services\Manage\RegionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegionController extends Controller
{
    private RegionService $service;

    public function __construct(RegionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $query = Region::orderByDesc('updated_at')
//            ->whereIn('type', [Region::REGION, Region::CAPITAL])
            ->regions();

        if (!empty($value = $request->get('name'))) {
            $query->where(function (Builder $query) use ($value) {
                $query->where('name_uz', 'ilike', '%' . $value . '%')
                    ->orWhere('name_uz_cy', 'ilike', '%' . $value . '%')
                    ->orWhere('name_ru', 'ilike', '%' . $value . '%')
                    ->orWhere('name_en', 'ilike', '%' . $value . '%');
            });
        }

        $regions = $query->paginate(20)->appends('name', $request->get('name'));

        return view('admin.regions.index', compact('regions'));
    }

    public function create(Request $request): View
    {
        $parent = null;

        if ($request->get('parent')) {
            $parent = Region::findOrFail($request->get('parent'));
        }

        return view('admin.regions.create', compact('parent'));
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $region = $this->service->create($request);
        session()->flash('message', 'запись обновлён ');
        return redirect()->route('dashboard.regions.show', $region);
    }

    public function show(Region $region): View
    {
        return view('admin.regions.show', compact('region'));
    }

    public function edit(Region $region): View
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(UpdateRequest $request, Region $region): RedirectResponse
    {
        $this->service->update($region->id, $request);
        session()->flash('message', 'запись обновлён ');
        return redirect()->route('dashboard.regions.show', $region);
    }

    public function destroy(Region $region): RedirectResponse
    {
        $parentId = $region->parent_id;
        if ($region->created_by !== Auth::user()->id && !Auth::user()->isAdmin()) {
            if ($parentId) {
                return redirect()->route('dashboard.regions.show', $parentId);
            }

            return redirect()->route('dashboard.regions.index');
        }

        try {
            $region->delete();

            session()->flash('message', 'запись обновлён ');

            if ($parentId) {
                return redirect()->route('dashboard.regions.show', $parentId);
            }

            return redirect()->route('dashboard.regions.index');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
