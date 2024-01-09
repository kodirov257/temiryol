@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

@section($cssSectionName)
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px;
        }
    </style>
@endsection

@include('layouts.admin.flash')

<div class="row">
    <div class="col-md-12">
        <div class="card card-gray card-outline">
            <div class="card-header"><h3 class="card-title"></h3></div>
            <div class="card-body">
                <div class="form-group">
                    {!! Html::label('Nomi', 'name_uz')->class('col-form-label') !!}
                    {!! Html::text('name_uz', old('name_uz', $organization->name_uz ?? null))->class('form-control' . ($errors->has('name_uz') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('name_uz'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name_uz') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label('Номи', 'name_uz_cy')->class('col-form-label') !!}
                    {!! Html::text('name_uz_cy', old('name_uz_cy', $organization->name_uz_cy ?? null))->class('form-control' . ($errors->has('name_uz_cy') ? ' is-invalid' : '')) !!}
                    @if($errors->has('name_uz_cy'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name_uz_cy') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label('Название', 'name_ru')->class('col-form-label') !!}
                    {!! Html::text('name_ru', old('name_ru', $organization->name_ru ?? null))->class('form-control' . ($errors->has('name_ru') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('name_ru'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name_ru') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label('Name', 'name_en')->class('col-form-label') !!}
                    {!! Html::text('name_en', old('name_en', $organization->name_en ?? null))->class('form-control' . ($errors->has('name_en') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('name_en'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name_en') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label(__('adminlte.region.name'), 'region_id')->class('col-form-label') !!}
                    {!! Html::select('region_id', $organization && $organization->region_id ? $defaultRegion : [],
                            old('region_id', $organization->region_id ?? null))
                            ->id('region_id')->class('form-control' . ($errors->has('region_id') ? ' is-invalid' : '')) !!}
                    {{--                            {!! Html::hidden('region_id', $celebrity->region_id ?? null) !!}--}}
                    @if($errors->has('region_id'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('region_id') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label(trans('adminlte.type'), 'type')->class('col-form-label') !!}
                    {!! Html::select('type', \App\Models\Organization::typeList(), old('type', $organization->type ?? null))
                            ->class('form-control' . ($errors->has('type') ? ' is-invalid' : ''))
                            ->required() !!}
                    @if($errors->has('type'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('type') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label('Slug', 'slug')->class('col-form-label') !!}
                    {!! Html::text('slug', old('slug', $organization->slug ?? null))->class('form-control' . ($errors->has('slug') ? ' is-invalid' : '')) !!}
                    @if ($errors->has('slug'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ trans('adminlte.' . ($organization ? 'edit' : 'save')) }}</button>
</div>

@section($javaScriptSectionName)
    <script>
        $('#region_id').select2({
            ajax: {
                url: '/api/search-regions',
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
                        results: data.data.regions,
                        pagination: {
                            more: (params.page * 10) < data.data.total,
                        },
                    };
                },
                delay: 250,
            },
            placeholder: '{{ __('adminlte.region.name') }}',
            minimumInputLength: 2,
            allowClear: true,
            templateResult: function (region) {
                if (region.loading) {
                    return region.text;
                }

                return region.name || region.text;
            },
            templateSelection: function (region) {
                return region.name || region.text;
            },
        });
    </script>

@endsection
