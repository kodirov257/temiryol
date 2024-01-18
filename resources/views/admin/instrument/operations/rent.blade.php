<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.instruments.operations.rent', $instrument) }}" method="POST">
            @csrf

{{--            @include('partials.admin._nav')--}}

            @include('admin.instrument.operations._form', ['operation' => null, 'operationType' => \App\Services\Manage\Instrument\OperationService::RENT])
        </form>
    @endsection
</x-admin-page-layout>
