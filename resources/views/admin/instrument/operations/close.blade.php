<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.instruments.operations.close', ['instrument' => $instrument, 'operation' => $operation]) }}" method="POST">
            @csrf

{{--            @include('partials.admin._nav')--}}

            @include('admin.instrument.operations._form', ['operationType' => \App\Services\Manage\Instrument\OperationService::CLOSE])
        </form>
    @endsection
</x-admin-page-layout>
