<x-admin-page-layout>
    @section('content')
        <form method="POST" action="{{ route('dashboard.users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.users._form')
        </form>
    @endsection
</x-admin-page-layout>
