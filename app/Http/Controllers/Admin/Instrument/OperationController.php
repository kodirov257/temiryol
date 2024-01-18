<?php

namespace App\Http\Controllers\Admin\Instrument;

use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Instrument\Operations\CloseRequest;
use App\Http\Requests\Admin\Instrument\Operations\CreateRequest;
use App\Http\Requests\Admin\Instrument\Operations\ProlongRequest;
use App\Http\Requests\Admin\Instrument\Operations\UpdateRequest;
use App\Models\Department;
use App\Models\Instrument\Instrument;
use App\Models\Instrument\InstrumentType;
use App\Models\Instrument\Operation;
use App\Services\Manage\Instrument\OperationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OperationController extends Controller
{
    private OperationService $service;

    public function __construct(OperationService $service)
    {
        $this->service = $service;
    }

    public function index(Instrument $instrument): View
    {
        $operations = OperationService::getOperationsGrouped($instrument->id);

        return view('admin.instrument.operations.index', compact('instrument', 'operations'));
    }

    public function indexAll(Request $request)
    {
        $query = Operation::with('instrument.departmentInstrumentType.type')->orderByDesc('updated_at');

        if (!empty($value = $request->get('type'))) {
            $query->where('type', $value);
        }

        if (!empty($value = $request->get('serial'))) {
            $query->where('serial', 'ilike', '%' . $value . '%');
        }

        if (!empty($value = $request->get('instrument_type'))) {
            $query->where('instrument_type_id', $value);
        }

        $defaultDepartment = [];
        if (!empty($value = $request->get('department'))) {
            $query->where('department_id', $value);

            $defaultDepartment = Department::where('id', $value)->get()
                ->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix(), 'id')->toArray();
        }

        $operations = $query->paginate(20)
            ->appends('type', $request->get('type'))
            ->appends('instrument_type', $request->get('instrument_type'))
            ->appends('department', $request->get('department'))
            ->appends('serial', $request->get('serial'));

        $types = InstrumentType::orderBy('name_' . LanguageHelper::getCurrentLanguagePrefix())
            ->pluck('name_' . LanguageHelper::getCurrentLanguagePrefix(), 'id')->toArray();

        return view('admin.instrument.operations.index-all', compact('operations', 'types', 'defaultDepartment'));
    }

    public function rentForm(Instrument $instrument): View
    {
        return view('admin.instrument.operations.rent', compact('instrument'));
    }

    public function rent(CreateRequest $request, Instrument $instrument): RedirectResponse
    {
        try {
            $operation = $this->service->rent($instrument->id, $request);
            session()->flash('message', 'запись обновлён');
            return redirect()->route('dashboard.instruments.operations.show', ['instrument' => $instrument, 'operation' => $operation]);
        } catch (ValidationException $e) {
            return redirect()->back($e->status)->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function prolongForm(Instrument $instrument, Operation $operation): View
    {
        return view('admin.instrument.operations.prolong', compact('instrument', 'operation'));
    }

    public function prolong(ProlongRequest $request, Instrument $instrument, Operation $operation): RedirectResponse
    {
        try {
            if ($operation->isClosed()) {
                throw new \RuntimeException(trans('adminlte.operation.is_closed'));
            }

            $newOperation = $this->service->prolong($operation->id, $request);
            session()->flash('message', 'запись обновлён');
            return redirect()->route('dashboard.instruments.operations.show', ['instrument' => $instrument, 'operation' => $newOperation]);
        } catch (ValidationException $e) {
            return redirect()->back($e->status)->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function closeForm(Instrument $instrument, Operation $operation): View
    {
        return view('admin.instrument.operations.close', compact('instrument', 'operation'));
    }

    public function close(CloseRequest $request, Instrument $instrument, Operation $operation): RedirectResponse
    {
        try {
            if ($operation->isClosed()) {
                throw new \RuntimeException(trans('adminlte.operation.is_closed'));
            }

            $operation = $this->service->close($operation->id, $request);
            session()->flash('message', 'запись обновлён');
            return redirect()->route('dashboard.instruments.operations.show', ['instrument' => $instrument, 'operation' => $operation]);
        } catch (ValidationException $e) {
            return redirect()->back($e->status)->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Instrument $instrument, Operation $operation): View
    {
        $relatedOperations = Operation::where('unique_id', $operation)
            ->whereNot('id', $operation->id)->orderBy('id')->get();

        return view('admin.instrument.operations.show', compact('instrument', 'operation', 'relatedOperations'));
    }

    public function edit(Instrument $instrument, Operation $operation): View
    {
        return view('admin.instrument.operations.edit', compact('instrument', 'operation'));
    }

    public function update(UpdateRequest $request, Instrument $instrument, Operation $operation): RedirectResponse
    {
        try {
            if ($operation->isClosed()) {
                throw new \RuntimeException(trans('adminlte.operation.is_closed'));
            }

            if ($operation->isProlonged()) {
                throw new \RuntimeException(trans('adminlte.operation.is_prolonged'));
            }

            $this->service->update($instrument->id, $request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.instruments.operations.show', ['instrument' => $instrument, 'operation' => $operation]);
        } catch (ValidationException $e) {
            return redirect()->back($e->status)->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
