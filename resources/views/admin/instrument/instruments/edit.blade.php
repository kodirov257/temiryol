<x-admin-page-layout>
    @section('content')
        <form
            action="{{ route('dashboard.department-instrument-types.instruments.update', ['departmentInstrumentType' => $departmentInstrumentType, 'instrument' => $instrument]) }}"
            method="POST">
            @csrf
            @method('PUT')

            {{--            @include('partials.admin._nav')--}}

            @include('admin.instrument.instruments._form')
        </form>
    @endsection
</x-admin-page-layout>
