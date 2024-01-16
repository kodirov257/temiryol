@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

<?php
/* @var $instruments \App\Models\Instrument[] */
?>

<x-admin-page-layout>
    @section($cssSectionName)
        <style>
            .select2-container--default .select2-selection--single {
                height: 38px;
            }
        </style>
    @endsection

    @section('content')
        <p><a href="{{ route('dashboard.instruments.create') }}" class="btn btn-success">{{ trans('adminlte.instrument.add') }}</a></p>

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
                                {!! Html::select('department_id', $defaultDepartment, request('department_id'))
                                    ->id('department_id')->class('form-control')->placeholder(trans('adminlte.organization.name')) !!}
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
                <td>{{ __('adminlte.image') }}</td>
                <td>Nomi</td>
                <td>Номи</td>
                <td>Название</td>
                <td>{{ __('adminlte.department.name') }}</td>
                <td>{{ __('adminlte.quantity') }}</td>
                <td>{{ __('adminlte.weight') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($instruments as $instrument)
                <tr>
                    <td>
                        @if ($instrument->photo)
                            <a href="{{ $instrument->photoOriginal }}" target="_blank"><img src="{{ $instrument->photoThumbnail }}" alt="{{ $instrument->name }}"></a>
                        @endif
                    </td>
                    <td><a href="{{ route('dashboard.instruments.show', $instrument) }}">{{ $instrument->name_uz }}</a></td>
                    <td><a href="{{ route('dashboard.instruments.show', $instrument) }}">{{ $instrument->name_uz_cy }}</a></td>
                    <td><a href="{{ route('dashboard.instruments.show', $instrument) }}">{{ $instrument->name_ru }}</a></td>
                    <td><a href="{{ route('dashboard.departments.show', $instrument->department) }}">{{ $instrument->department->hierarchy }}</a></td>
                    <td>{{ $instrument->quantity }}</td>
                    <td>{{ $instrument->weight }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $instruments->links() }}
    @endsection

    @section($javaScriptSectionName)
        <script>
            $('#department_id').select2({
                ajax: {
                    url: '/api/search-departments',
                    method: 'GET',
                    dataType: 'json',
                    headers: {'Accept-Language': '{{ App::getLocale() }}'},
                    data: function (params) {
                        return {
                            name: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: data.data.departments,
                            pagination: {
                                more: (params.page * 10) < data.data.total,
                            },
                        };
                    },
                    delay: 250,
                },
                placeholder: '{{ __('adminlte.department.name') }}',
                minimumInputLength: 2,
                allowClear: true,
                templateResult: function (department) {
                    if (department.loading) {
                        return department.text;
                    }

                    return department.name || department.text;
                },
                templateSelection: function (department) {
                    return department.name || department.text;
                },
            });
        </script>

    @endsection
</x-admin-page-layout>
