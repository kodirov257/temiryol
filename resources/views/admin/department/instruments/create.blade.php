<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.departments.instruments.store', $department) }}" method="POST">
            @csrf

{{--            @include('partials.admin._nav')--}}

            @include('admin.department.instruments._form', ['instrument' => null])
        </form>
    @endsection
</x-admin-page-layout>
