<x-admin-page-layout>
    @section('content')
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('dashboard.department-instrument-types.instruments.edit', ['departmentInstrumentType' => $departmentInstrumentType, 'instrument' => $instrument]) }}" class="btn btn-primary mr-1">
                {{ __('adminlte.edit') }}
            </a>
            <a href="{{ route('dashboard.department-instrument-types.instruments.destroy.form', ['departmentInstrumentType' => $departmentInstrumentType, 'instrument' => $instrument]) }}" class="btn btn-danger mr-1">
                {{ __('adminlte.delete') }}
            </a>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header"><h3 class="card-title">{{ __('adminlte.main') }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped projects">
                            <tbody>
                            <tr><th>ID</th><td>{{ $instrument->id }}</td></tr>
                            <tr>
                                <th>{{ __('adminlte.name') }}</th>
                                <td>
                                    <a href="{{ route('dashboard.instrument-types.show', $instrument->departmentInstrumentType->type) }}">
                                        {{ $instrument->departmentInstrumentType->type->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr><th>{{ __('adminlte.instrument.serial') }}</th><td>{{ $instrument->serial }}</td></tr>
                            <tr><th>{{ __('adminlte.status') }}</th><td>{!! $instrument->statusLabel() !!}</td></tr>
                            <tr><th>{{ __('adminlte.instrument.notes') }}</th><td>{{ $instrument->notes }}</td></tr>
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
                                <td><a href="{{ route('dashboard.users.show', $instrument->createdBy) }}">{{ $instrument->createdBy->name }}</a></td>
                            </tr>
                            <tr>
                                <th>{{ __('adminlte.updated_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $instrument->updatedBy) }}">{{ $instrument->updatedBy->name }}</a></td>
                            </tr>
                            <tr><th>{{ __('adminlte.created_at') }}</th><td>{{ $instrument->created_at }}</td></tr>
                            <tr><th>{{ __('adminlte.updated_at') }}</th><td>{{ $instrument->updated_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-admin-page-layout>
