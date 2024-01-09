<x-admin-page-layout>
    @section('content')
        <p><a href="{{ route('dashboard.departments.create') }}" class="btn btn-success">{{ trans('adminlte.department.add') }}</a></p>

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
                <td>Nomi</td>
                <td>Номи</td>
                <td>Название</td>
                <td>{{ __('adminlte.organization.name') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($departments as $department)
                <tr>
                    <td><a href="{{ route('dashboard.departments.show', $department) }}">{{ $department->name_uz }}</a></td>
                    <td><a href="{{ route('dashboard.departments.show', $department) }}">{{ $department->name_uz_cy }}</a></td>
                    <td><a href="{{ route('dashboard.departments.show', $department) }}">{{ $department->name_ru }}</a></td>
                    <td><a href="{{ route('dashboard.organizations.show', $department->organization) }}">{{ $department->organization->name }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $departments->links() }}
    @endsection
</x-admin-page-layout>
