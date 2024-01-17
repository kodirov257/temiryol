<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.department-instrument-types.instruments.store', $departmentInstrumentType) }}" method="POST">
            @csrf

{{--            @include('partials.admin._nav')--}}

            @include('admin.instrument.instruments._form', ['instrument' => null])
        </form>
    @endsection
</x-admin-page-layout>
