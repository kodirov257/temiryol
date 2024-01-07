<x-admin-page-layout>
    @section('content')
        <form method="POST" action="{{ route('dashboard.users.store') }}" enctype="multipart/form-data">
            @csrf

            @include('admin.users._form', ['user' => null])
        </form>
    @endsection
</x-admin-page-layout>
