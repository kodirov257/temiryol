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
                    {!! Html::text('name_uz', old('name_uz', $department->name_uz ?? null))->class('form-control' . ($errors->has('name_uz') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('name_uz'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name_uz') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label('Номи', 'name_uz_cy')->class('col-form-label') !!}
                    {!! Html::text('name_uz_cy', old('name_uz_cy', $department->name_uz_cy ?? null))->class('form-control' . ($errors->has('name_uz_cy') ? ' is-invalid' : '')) !!}
                    @if($errors->has('name_uz_cy'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name_uz_cy') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label('Название', 'name_ru')->class('col-form-label') !!}
                    {!! Html::text('name_ru', old('name_ru', $department->name_ru ?? null))->class('form-control' . ($errors->has('name_ru') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('name_ru'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name_ru') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label('Name', 'name_en')->class('col-form-label') !!}
                    {!! Html::text('name_en', old('name_en', $department->name_en ?? null))->class('form-control' . ($errors->has('name_en') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('name_en'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name_en') }}</strong></span>
                    @endif
                </div>
                @if(!request()->get('organization_id') || !request()->get('parent'))
                    <div class="form-group">
                        {!! Html::label(__('adminlte.organization.name'), 'organization_id')->class('col-form-label') !!}
                        {!! Html::select('organization_id', $organizationList, old('organization_id', $parent->organization_id ?? $department->organization_id ?? null))
                                ->id('organization_id')->class('form-control' . ($errors->has('organization_id') ? ' is-invalid' : '')) !!}
                        @if($errors->has('organization_id'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('organization_id') }}</strong></span>
                        @endif
                    </div>
                @endif
                @if($department)
                    <div class="form-group">
                        {!! Html::label(__('adminlte.department.parent'), 'parent_id')->class('col-form-label') !!}
                        {!! Html::select('parent_id', $departmentList, old('parent_id', $department->parent_id ?? null))
                                ->id('parent_id')->class('form-control' . ($errors->has('parent_id') ? ' is-invalid' : '')) !!}
                        @if($errors->has('parent_id'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('parent_id') }}</strong></span>
                        @endif
                    </div>
                @endif
                <div class="form-group">
                    {!! Html::label('Slug', 'slug')->class('col-form-label') !!}
                    {!! Html::text('slug', old('slug', $department->slug ?? null))->class('form-control' . ($errors->has('slug') ? ' is-invalid' : '')) !!}
                    @if ($errors->has('slug'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ trans('adminlte.' . ($department ? 'edit' : 'save')) }}</button>
</div>

@section($javaScriptSectionName)
    <script>
        $('#organization_id').select2({
            placeholder: '{{ __('adminlte.organization.name') }}',
        });
        $('#parent_id').select2({
            placeholder: '{{ __('adminlte.department.name') }}',
        });
    </script>

@endsection
