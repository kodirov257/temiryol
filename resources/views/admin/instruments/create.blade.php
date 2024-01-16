<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.instrument-types.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('partials.admin._nav')

            @include('admin.instruments._form', ['instrument' => null])
        </form>
    @endsection
</x-admin-page-layout>
