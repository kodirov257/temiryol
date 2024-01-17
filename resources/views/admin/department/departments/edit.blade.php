<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.departments.update', $department) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.department.departments._form', ['parent' => null])
        </form>
    @endsection
</x-admin-page-layout>
