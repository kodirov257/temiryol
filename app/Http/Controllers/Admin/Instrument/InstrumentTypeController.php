<?php

namespace App\Http\Controllers\Admin\Instrument;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Instrument\InstrumentTypes\CreateRequest;
use App\Http\Requests\Admin\Instrument\InstrumentTypes\UpdateRequest;
use App\Models\Instrument\InstrumentType;
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
        $query = InstrumentType::orderByDesc('updated_at');

        if (!empty($value = $request->get('name'))) {
            $query->where(function (Builder $query) use ($value) {
                $query->where('name_uz', 'ilike', '%' . $value . '%')
                    ->orWhere('name_uz_cy', 'ilike', '%' . $value . '%')
                    ->orWhere('name_ru', 'ilike', '%' . $value . '%')
                    ->orWhere('name_en', 'ilike', '%' . $value . '%');
            });
        }

        $instrumentTypes = $query->paginate(20)
            ->appends('name', $request->get('name'));

        return view('admin.instrument.instrument-types.index', compact('instrumentTypes'));
    }

    public function create(): View
    {
        return view('admin.instrument.instrument-types.create');
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        try {
            $instrument = $this->service->create($request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.instrument-types.show', $instrument);
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(InstrumentType $instrumentType): View
    {
        return view('admin.instrument.instrument-types.show', compact('instrumentType'));
    }

    public function edit(InstrumentType $instrumentType): View
    {
        return view('admin.instrument.instrument-types.edit', compact('instrumentType'));
    }

    public function update(UpdateRequest $request, InstrumentType $instrumentType): RedirectResponse
    {
        try {
            $this->service->update($instrumentType->id, $request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.instrument-types.show', $instrumentType);
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

    public function destroy(InstrumentType $instrumentType): RedirectResponse
    {
        if ($instrumentType->created_by !== Auth::user()->id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard.instrument-types.index');
        }

        try {
            $instrumentType->delete();

            session()->flash('message', 'запись обновлён ');

            return redirect()->route('dashboard.instrument-types.index');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
