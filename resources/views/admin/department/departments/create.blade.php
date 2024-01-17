<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.departments.store', ['parent' => $parent ? $parent->id : null]) }}" method="POST">
            @csrf

            @include('admin.departments._form', ['department' => null])
        </form>
    @endsection
</x-admin-page-layout>
