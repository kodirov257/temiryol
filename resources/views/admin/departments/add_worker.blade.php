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
                height: 40px;
            }
        </style>
    @endsection

    @include('layouts.admin.flash')

    @section('content')
        <form action="{{ route('dashboard.departments.employees.add', ['department' => $department]) }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-gray card-outline">
                        <div class="card-header"><h3 class="card-title"></h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                {!! Html::label(__('adminlte.full_name'), 'worker_id')->class('col-form-label') !!}
                                {!! Html::select('worker_id', [], old('worker_id'))->id('worker_id')
                                    ->class('form-control' . ($errors->has('worker_id') ? ' is-invalid' : '')) !!}
                                @if($errors->has('worker_id'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('worker_id') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('adminlte.add') }}</button>
            </div>
        </form>
    @endsection

    @section($javaScriptSectionName)
        <script>
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
        </script>

    @endsection
</x-admin-page-layout>
