@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

<?php
/* @var $instruments \App\Models\Instrument\Instrument[] */
?>

<x-admin-page-layout>
    @section($cssSectionName)
        <style>
            .select2-container--default .select2-selection--single {
                height: 40px;
            }
        </style>
    @endsection

    @section('content')
        <div class="card mb-4">
            <div class="card-body">
                <form action="?" method="GET">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                {!! Html::text('serial', request('serial'))->class('form-control')->placeholder(trans('adminlte.instrument.serial')) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Html::select('type', $types, request('type'))
                                    ->id('type')->class('form-control')->placeholder(trans('adminlte.instrument_type.name')) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                @if(empty($defaultDepartment))
                                    {!! Html::select('department', [], request('department'))
                                        ->id('department')->class('form-control')->placeholder(trans('adminlte.department.name')) !!}
                                @else
                                    {!! Html::select('department', $defaultDepartment, request('department'))
                                        ->id('department')->class('form-control') !!}
                                @endif
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
                <td>{{ __('adminlte.instrument.serial') }}</td>
                <td>{{ __('adminlte.name') }}</td>
                <td>{{ __('adminlte.department.name') }}</td>
                <td>{{ __('adminlte.status') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($instruments as $instrument)
                <tr>
                    <td>
                        <a href="{{ route('dashboard.department-instrument-types.instruments.show', ['departmentInstrumentType' => $instrument->departmentInstrumentType, 'instrument' => $instrument]) }}">
                            {{ $instrument->serial }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('dashboard.instrument-types.show', $instrument->departmentInstrumentType->type) }}">
                            {{ $instrument->departmentInstrumentType->type->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('dashboard.departments.show', $instrument->departmentInstrumentType->department) }}">
                            {{ $instrument->departmentInstrumentType->department->hierarchy }}
                        </a>
                    </td>
                    <td>
                        {!! $instrument->statusLabel() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $instruments->links() }}
    @endsection

    @section($javaScriptSectionName)
        <script>
            $('#type').select2();
            $('#department').select2({
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
