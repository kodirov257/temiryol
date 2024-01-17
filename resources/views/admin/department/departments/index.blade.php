@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

<x-admin-page-layout>
    @section($cssSectionName)
        <style>
            .select2-container--default .select2-selection--single {
                height: 38px;
            }
        </style>
    @endsection

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
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Html::select('organization_id', $organizations, request('organization_id'))
                                    ->id('organization_id')->class('form-control')->placeholder(trans('adminlte.organization.name')) !!}
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

    @section($javaScriptSectionName)
        <script>
            $('#organization_id').select2({
                placeholder: '{{ __('adminlte.organization.name') }}',
            });
        </script>

    @endsection
</x-admin-page-layout>
