<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.organizations.update', $organization) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.organizations._form')
        </form>
    @endsection
</x-admin-page-layout>
