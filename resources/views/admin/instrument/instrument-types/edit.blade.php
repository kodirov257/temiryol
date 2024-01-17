<x-admin-page-layout>
    @section('content')
        <form action="{{ route('dashboard.instrument-types.update', $instrumentType) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('partials.admin._nav')

            @include('admin.instrument.instrument-types._form')
        </form>
    @endsection
</x-admin-page-layout>
