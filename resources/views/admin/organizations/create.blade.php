<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.organizations.store', ['parent' => $parent ? $parent->id : null]) }}" method="POST">
            @csrf

            @include('admin.organizations._form', ['organization' => null])
        </form>
    @endsection
</x-admin-page-layout>
