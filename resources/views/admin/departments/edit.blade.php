<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.departments.update', $department) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.departments._form')
        </form>
    @endsection
</x-admin-page-layout>
