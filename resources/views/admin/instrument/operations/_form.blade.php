<?php
use \App\Services\Manage\Instrument\OperationService;
?>

@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

@section($cssSectionName)
    @if(in_array($operationType, [OperationService::RENT, OperationService::EDIT], true))
        <style>
            .select2-container--default .select2-selection--single {
                height: 38px;
            }
        </style>
    @endif
@endsection

@include('layouts.admin.flash')

<div class="row">
    <div class="col-md-12">
        <div class="card card-gray card-outline">
            <div class="card-header"><h3 class="card-title"></h3></div>
            <div class="card-body">
                <div class="row">
                    @if(in_array($operationType, [OperationService::RENT, OperationService::EDIT], true))
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Html::label(__('adminlte.operation.renter'), 'borrower_id')->class('col-form-label') !!}
                                {!! Html::select('borrower_id', [], old('borrower_id'))->id('borrower_id')
                                        ->class('form-control' . ($errors->has('borrower_id') ? ' is-invalid' : '')) !!}
                                @if($errors->has('borrower_id'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('borrower_id') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(in_array($operationType, [OperationService::RENT, OperationService::EDIT, OperationService::PROLONG], true))
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Html::label(__('adminlte.operation.deadline_date'), 'deadline_date')->class('col-form-label') !!}
                                {!! Html::date('deadline_date', old('deadline_date', $operation && $operation->deadline ? $operation->deadline->format('Y-m-d') : null))
                                        ->class('form-control' . ($errors->has('deadline_date') ? ' is-invalid' : '')) !!}
                                @if($errors->has('deadline_date'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('deadline_date') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::label(__('adminlte.operation.deadline_time'), 'deadline_time')->class('col-form-label') !!}
                                {!! Html::time('deadline_time', old('deadline_time', $operation && $operation->deadline ? $operation->deadline->format('H:i') : null))
                                        ->class('form-control' . ($errors->has('deadline_time') ? ' is-invalid' : '')) !!}
                                @if($errors->has('deadline_time'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('deadline_time') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($operationType === OperationService::CLOSE)
                        <div class="form-group">
                            {!! Html::label(__('adminlte.operation.instrument_status'), 'instrument_status')->class('col-form-label') !!}
                            {!! Html::select('instrument_status', \App\Models\Instrument\Instrument::statusList(), old('instrument_status'))
                                    ->class('form-control' . ($errors->has('instrument_status') ? ' is-invalid' : ''))->placeholder('') !!}
                            @if($errors->has('instrument_status'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('instrument_status') }}</strong></span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Html::label(__('adminlte.notes'), 'notes')->class('col-form-label'); !!}
                    <br>
                    {!! Html::textarea('notes', old('notes', $operation->notes ?? null))
                            ->class('form-control' . $errors->has('notes') ? ' is-invalid' : '')
                            ->id('notes')->rows(10)->required(); !!}
                    @if ($errors->has('notes'))
                        <span
                            class="invalid-feedback"><strong>{{ $errors->first('notes') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ trans('adminlte.' . ($operation ? 'edit' : 'save')) }}</button>
</div>

@section($javaScriptSectionName)
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    <script>
        CKEDITOR.replace('notes');

        $('#borrower_id').select2({
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
