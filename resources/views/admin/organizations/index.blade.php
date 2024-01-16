<x-admin-page-layout>
    @section('content')
        <p><a href="{{ route('dashboard.organizations.create') }}" class="btn btn-success">{{ trans('adminlte.region.add') }}</a></p>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <td>{{ __('adminlte.name') }}</td>
                <td>{{ __('adminlte.type') }}</td>
                <td>{{ __('adminlte.region.name') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($organizations as $organization)
                <tr>
                    <td>
                        @for ($i = 0; $i < $organization->depth; $i++) &mdash; @endfor
                        <a href="{{ route('dashboard.organizations.show', $organization) }}">{{ $organization->name }}</a>
                    </td>
                    <td>{{ $organization->typeName() }}</td>
                    <td><a href="{{ route('dashboard.regions.show', $organization->region) }}">{{ $organization->region->place }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endsection
</x-admin-page-layout>
