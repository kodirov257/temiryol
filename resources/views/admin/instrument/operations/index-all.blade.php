@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

<?php /* @var $operations \App\Models\Instrument\Operation[] */ ?>

<x-admin-page-layout>
    @section($cssSectionName)
        <style>
            .select2-container--default .select2-selection--single {
                height: 38px;
            }
        </style>
    @endsection

    @section('content')
        @php($filter = request()->has('filter') && !empty(request()->get('filter')) ? request()->get('filter') : null)
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ !$filter ? 'active' : '' }}" href="{{ route('dashboard.operations.index') }}">
                    {{ __('adminlte.all') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'active' ? 'active' : '' }}" href="{{ route('dashboard.operations.index', ['filter' => 'active']) }}">
                    {{ __('adminlte.active') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'prolonged' ? 'active' : '' }}" href="{{ route('dashboard.operations.index', ['filter' => 'prolonged']) }}">
                    {{ __('adminlte.prolonged') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'expiring' ? 'active' : '' }}" href="{{ route('dashboard.operations.index', ['filter' => 'expiring']) }}">
                    {{ __('adminlte.expiring') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'expired' ? 'active' : '' }}" href="{{ route('dashboard.operations.index', ['filter' => 'expired']) }}">
                    {{ __('adminlte.expired') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'closed' ? 'active' : '' }}" href="{{ route('dashboard.operations.index', ['filter' => 'closed']) }}">
                    {{ __('adminlte.closed') }}
                </a>
            </li>
        </ul>

        <div class="card mb-4">
            <div class="card-body">
                <form action="?" method="GET" id="operation-search">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                {!! Html::text('serial', request('serial'))->class('form-control')->id('serial')
                                    ->placeholder(trans('adminlte.instrument.serial')) !!}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                {!! Html::select('instrument_type', $types, request('instrument_type'))
                                    ->id('instrument_type')->class('form-control')->placeholder(trans('adminlte.instrument_type.name')) !!}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                @if(empty($defaultUser))
                                    {!! Html::select('worker_id', [], request('worker_id'))
                                        ->id('worker_id')->class('form-control')->placeholder(trans('adminlte.user.role_worker')) !!}
                                @else
                                    {!! Html::select('worker_id', $defaultUser, request('worker_id'))
                                        ->id('worker_id')->class('form-control') !!}
                                @endif
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
                                {!! Html::button(trans('adminlte.search'), 'submit')->class('btn btn-primary')->id('submit-button') !!}
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
                <td>{{ __('adminlte.instrument.serial') }}</td>
                <td>{{ __('adminlte.type') }}</td>
                <td>{{ __('adminlte.status') }}</td>
                <td>{{ __('adminlte.operation.instrument_status') }}</td>
                <td>{{ __('adminlte.operation.renter') }}</td>
                <td></td>
            </tr>
            </thead>
            <tbody id="tbody-operations">
            @foreach($operations as $operation)
                <tr>
                    <td>
                        <a href="{{ route('dashboard.department-instrument-types.instruments.show',
                                        ['departmentInstrumentType' => $operation->instrument->departmentInstrumentType, 'instrument' => $operation->instrument]) }}"
                        >
                            {{ $operation->instrumentType->name }}
                        </a>
                    </td>
                    <td>{{ $operation->serial }}</td>
                    <td>{{ $operation->typeName() }}</td>
                    <td>
                        {!! $operation->statusLabel() !!}
                    </td>
                    <td>{!! \App\Helpers\InstrumentHelper::statusLabel($operation->instrument_status) !!}</td>
                    <td>
                        <a href="{{ route('dashboard.users.show', $operation->borrower) }}">{{ $operation->borrower->profile->fullName }}</a>
                    </td>
                    <td>
                        <a href="{{ route('dashboard.instruments.operations.show', ['instrument' => $operation->instrument, 'operation' => $operation]) }}"
                           class="btn btn-primary mr-1">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $operations->links() }}
    @endsection

    @section($javaScriptSectionName)
        <script>
            $('#instrument_type').select2();
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

            $('#worker_id').select2({
                ajax: {
                    url: '/api/search-users',
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
                            results: data.data.users,
                            pagination: {
                                more: (params.page * 10) < data.data.total,
                            },
                        };
                    },
                    delay: 250,
                },
                placeholder: '{{ __('adminlte.full_name') }}',
                minimumInputLength: 2,
                allowClear: true,
                templateResult: function (user) {
                    if (user.loading) {
                        return user.text;
                    }

                    return user.name || user.text;
                },
                templateSelection: function (user) {
                    return user.name || user.text;
                },
            });

            $('#submit-button').click(function (e) {
                e.preventDefault();

                const serial = $('#serial').val();
                const workerId = $('#worker_id').val();
                const instrumentType = $('#instrument_type').val();
                const department = $('#department').val();
                const filter = '{{ $filter }}';
                let location = `${window.location.origin}${window.location.pathname}?serial=${serial}&worker_id=${workerId}&instrument_type=${instrumentType}&department=${department}`;

                if (filter !== '') {
                    location += `&filter=${filter}`;
                }

                window.location.href = location;
            });

            {{--setInterval(function () {--}}
            {{--    const serial = $('#serial').val();--}}
            {{--    const workerId = $('#worker_id').val();--}}
            {{--    const instrumentType = $('#instrument_type').val();--}}
            {{--    const department = $('#department').val();--}}
            {{--    const filter = '{{ $filter }}';--}}

            {{--    $.ajax({--}}
            {{--        url: `/api/search-operations?serial=${serial}&worker_id=${workerId}&instrument_type=${instrumentType}&department=${department}&filter=${filter}`,--}}
            {{--        type: 'GET',--}}
            {{--        dataType: 'json',--}}
            {{--        headers: {'Accept-Language': '{{ App::getLocale() }}'},--}}
            {{--        success: function (data, params) {--}}
            {{--            params.page = params.page || 1;--}}

            {{--            return {--}}
            {{--                results: data.data.users,--}}
            {{--                pagination: {--}}
            {{--                    more: (params.page * 10) < data.data.total,--}}
            {{--                },--}}
            {{--            };--}}
            {{--        },--}}
            {{--        error: function (error) {--}}
            {{--            console.log(error);--}}
            {{--        }--}}
            {{--    });--}}
            {{--}, 10000);--}}
        </script>

    @endsection
</x-admin-page-layout>
