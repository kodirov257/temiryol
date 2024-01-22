<x-admin-page-layout>
    @section('content')
        <div class="d-flex flex-row mb-3">
            @if(!$operation->isClosed())
                @if(!$operation->isProlonged())
                    <a href="{{ route('dashboard.instruments.operations.edit', ['instrument' => $instrument, 'operation' => $operation]) }}" class="btn btn-primary mr-1">
                        {{ __('adminlte.edit') }}
                    </a>
                @endif
                <a href="{{ route('dashboard.instruments.operations.prolong.form', ['instrument' => $instrument, 'operation' => $operation]) }}" class="btn btn-dark mr-1">
                    {{ __('adminlte.prolong') }}
                </a>
                <a href="{{ route('dashboard.instruments.operations.close.form', ['instrument' => $instrument, 'operation' => $operation]) }}" class="btn btn-success mr-1">
                    {{ __('adminlte.close') }}
                </a>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header"><h3 class="card-title">{{ __('adminlte.main') }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped projects">
                            <tbody>
                            <tr><th>ID</th><td>{{ $operation->id }}</td></tr>
                            <tr>
                                <th>{{ __('adminlte.name') }}</th>
                                <td>
                                    <a href="{{ route('dashboard.department-instrument-types.instruments.show', ['departmentInstrumentType' => $operation->instrument->departmentInstrumentType, 'instrument' => $instrument]) }}">
                                        {{ $operation->instrumentType->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr><th>{{ __('adminlte.instrument.serial') }}</th><td>{{ $operation->serial }}</td></tr>
                            <tr>
                                <th>{{ __('adminlte.operation.renter') }}</th>
                                <td>
                                    <a href="{{ route('dashboard.users.show', $operation->borrower) }}">
                                        {{ $operation->borrower->profile->fullName }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('adminlte.department.name') }}</th>
                                <td>
                                    <a href="{{ route('dashboard.departments.show', $operation->department) }}">
                                        {{ $operation->department->hierarchy }}
                                    </a>
                                </td>
                            </tr>
                            <tr><th>{{ __('adminlte.type') }}</th><td>{{ $operation->typeName() }}</td></tr>
                            <tr><th>{{ __('adminlte.operation.deadline') }}</th><td>{{ $operation->deadline }}</td></tr>
                            <tr><th>{{ __('adminlte.status') }}</th><td>{!! $operation->statusLabel() !!}</td></tr>
                            <tr>
                                <th>{{ __('adminlte.operation.instrument_status') }}</th>
                                <td>{!! \App\Helpers\InstrumentHelper::statusLabel($operation->instrument_status) !!}</td>
                            </tr>
                            <tr><th>{{ __('adminlte.notes') }}</th><td>{!! $operation->notes !!}</td></tr>
                            <tr>
                                <th>{{ __('adminlte.operation.related') }}</th>
                                <td>
                                    @foreach($relatedOperations as $relatedOperation)
                                        <a href="{{ route('dashboard.instruments.operations.show', ['instrument' => $instrument, 'operation' => $relatedOperation]) }}">
                                            {{ $relatedOperation->id }} - {{ $relatedOperation->serial }}
                                        </a> @if($relatedOperations->count() > 1) ->@endif
                                        <br>
                                    @endforeach
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-gray card-outline">
                    <div class="card-header"><h3 class="card-title">{{ __('adminlte.other') }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped projects">
                            <tbody>
                            <tr>
                                <th>{{ __('adminlte.created_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $operation->createdBy) }}">{{ $operation->createdBy->name }}</a></td>
                            </tr>
                            <tr>
                                <th>{{ __('adminlte.updated_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $operation->updatedBy) }}">{{ $operation->updatedBy->name }}</a></td>
                            </tr>
                            <tr><th>{{ __('adminlte.created_at') }}</th><td>{{ $operation->created_at }}</td></tr>
                            <tr><th>{{ __('adminlte.updated_at') }}</th><td>{{ $operation->updated_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-admin-page-layout>
