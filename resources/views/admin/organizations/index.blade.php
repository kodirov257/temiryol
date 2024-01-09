<x-admin-page-layout>
    @section('content')
        <p><a href="{{ route('dashboard.organizations.create') }}" class="btn btn-success">{{ trans('adminlte.region.add') }}</a></p>

        <div class="card mb-4">
            <div class="card-body">
                <form action="?" method="GET">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Html::text('name', request('name'))->class('form-control')->placeholder(trans('adminlte.name')) !!}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                {!! Html::button(trans('adminlte.search'), 'submit')->class('btn btn-primary') !!}
                                {!! Html::a('?', trans('adminlte.clear'))->class('btn btn-outline-secondary') !!}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
