<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.regions.store', ['parent' => $parent ? $parent->id : null]) }}"
              method="POST">
            @csrf

            @include('admin.regions._form', ['region' => null])
        </form>
    @endsection
</x-admin-page-layout>
