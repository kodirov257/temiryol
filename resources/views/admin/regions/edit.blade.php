<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.regions.update', $region) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.regions._form')
        </form>
    @endsection
</x-admin-page-layout>
