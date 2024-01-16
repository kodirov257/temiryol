<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.instrument-types.update', $instrument) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('partials.admin._nav')

            @include('admin.instruments._form')
        </form>
    @endsection
</x-admin-page-layout>
