<x-admin-page-layout>
    @section('content')
        <div class="d-flex flex-row mb-3">
            @if($instrument->isAvailable() || $instrument->isRepaired())
                <a href="{{ route('dashboard.instruments.operations.rent.form', $instrument) }}" class="btn btn-success mr-1">
                    {{ __('adminlte.rent') }}
                </a>
            @endif
        </div>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <td>{{ __('adminlte.name') }}</td>
                <td>{{ __('adminlte.instrument.serial') }}</td>
                <td>{{ __('adminlte.type') }}</td>
                <td>{{ __('adminlte.status') }}</td>
                <td>{{ __('adminlte.operation.instrument_status') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($operations as $operation)
                <tr>
                    <td>
                        @for ($i = 0; $i < $operation->depth; $i++) &mdash; @endfor
                        {{ $operation->instrumentType->name }}
                    </td>
                    <td>{{ $operation->serial }}</td>
                    <td>{{ $operation->typeName() }}</td>
                    <td>
                        {!! $operation->statusLabel() !!}
                    </td>
                    <td>{!! \App\Helpers\InstrumentHelper::statusLabel($operation->instrument_status) !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endsection
</x-admin-page-layout>
