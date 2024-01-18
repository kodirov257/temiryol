<x-admin-page-layout>
    @section('content')
        <form
            action="{{ route('dashboard.instruments.operations.update', ['instrument' => $instrument, 'operation' => $operation]) }}" method="POST">
            @csrf
            @method('PUT')

            {{--            @include('partials.admin._nav')--}}

            @include('admin.instrument.operations._form', ['operationType' => \App\Services\Manage\Instrument\OperationService::EDIT])
        </form>
    @endsection
</x-admin-page-layout>
