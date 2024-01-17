@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

@include('layouts.admin.flash')

<div class="row">
    <div class="col-md-12">
        <div class="card card-gray card-outline">
            <div class="card-header"><h3 class="card-title"></h3></div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="form-group">
                        {!! Html::label(__('adminlte.instrument.serial'), 'serial')->class('col-form-label') !!}
                        {!! Html::text('serial', old('serial', $instrument->serial ?? null))
                                ->class('form-control' . ($errors->has('serial') ? ' is-invalid' : ''))->required() !!}
                        @if($errors->has('serial'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('serial') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Html::label(__('adminlte.status'), 'status')->class('col-form-label') !!}
                        {!! Html::select('status', \App\Models\Instrument\Instrument::statusList(), old('status', $instrument->status ?? null))
                                ->class('form-control' . ($errors->has('status') ? ' is-invalid' : ''))
                                ->placeholder('')->required() !!}
                        @if($errors->has('status'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('status') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Html::label(__('adminlte.instrument.notes'), 'notes')->class('col-form-label'); !!}
                        <br>
                        {!! Html::textarea('notes', old('notes', $instrument->notes ?? null))
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
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ trans('adminlte.' . ($instrument ? 'edit' : 'save')) }}</button>
</div>

@section($javaScriptSectionName)
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    <script>
        CKEDITOR.replace('notes');
    </script>

@endsection
