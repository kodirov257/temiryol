@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

@section($cssSectionName)
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-fileinput/css/fileinput.css') }}">

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
                <div class="tab-content">
                    <div class="tab-pane active" id="uzbek" role="tabpanel">
                        <div class="form-group">
                            {!! Html::label('Nomi', 'name_uz')->class('col-form-label') !!}
                            {!! Html::text('name_uz', old('name_uz', $instrumentType->name_uz ?? null))->class('form-control' . ($errors->has('name_uz') ? ' is-invalid' : ''))->required() !!}
                            @if($errors->has('name_uz'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('name_uz') }}</strong></span>
                            @endif
                        </div>
                        <div class="form-group">
                            {!! Html::label('Tavsifi', 'description_uz')->class('col-form-label'); !!}
                            <br>
                            {!! Html::textarea('description_uz', old('description_uz', $instrumentType->description_uz ?? null))
                                    ->class('form-control' . $errors->has('description_uz') ? ' is-invalid' : '')
                                    ->id('description_uz')->rows(10)->required(); !!}
                            @if ($errors->has('description_uz'))
                                <span
                                    class="invalid-feedback"><strong>{{ $errors->first('description_uz') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="uzbek-cyrill" role="tabpanel">
                        <div class="form-group">
                            {!! Html::label('Номи', 'name_uz_cy')->class('col-form-label') !!}
                            {!! Html::text('name_uz_cy', old('name_uz_cy', $instrumentType->name_uz_cy ?? null))->class('form-control' . ($errors->has('name_uz_cy') ? ' is-invalid' : '')) !!}
                            @if($errors->has('name_uz_cy'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('name_uz_cy') }}</strong></span>
                            @endif
                        </div>
                        <div class="form-group">
                            {!! Html::label('Тавсифи', 'description_uz_cy')->class('col-form-label'); !!}
                            <br>
                            {!! Html::textarea('description_uz_cy', old('description_uz_cy', $instrumentType->description_uz_cy ?? null))
                                ->class('form-control' . $errors->has('description_uz_cy') ? ' is-invalid' : '')
                                ->id('description_uz_cy')->rows(10)->required(); !!}
                            @if ($errors->has('description_uz_cy'))
                                <span
                                    class="invalid-feedback"><strong>{{ $errors->first('description_uz_cy') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="russian" role="tabpanel">
                        <div class="form-group">
                            {!! Html::label('Название', 'name_ru')->class('col-form-label') !!}
                            {!! Html::text('name_ru', old('name_ru', $instrumentType->name_ru ?? null))->class('form-control' . ($errors->has('name_ru') ? ' is-invalid' : ''))->required() !!}
                            @if($errors->has('name_ru'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('name_ru') }}</strong></span>
                            @endif
                        </div>
                        <div class="form-group">
                            {!! Html::label('Описание', 'description_ru')->class('col-form-label'); !!}
                            <br>
                            {!! Html::textarea('description_ru', old('description_ru', $instrumentType->description_ru ?? null))
                                ->class('form-control' . $errors->has('description_ru') ? ' is-invalid' : '')
                                ->id('description_ru')->rows(10)->required(); !!}
                            @if ($errors->has('description_ru'))
                                <span
                                    class="invalid-feedback"><strong>{{ $errors->first('description_ru') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="english" role="tabpanel">
                        <div class="form-group">
                            {!! Html::label('Name', 'name_en')->class('col-form-label') !!}
                            {!! Html::text('name_en', old('name_en', $instrumentType->name_en ?? null))->class('form-control' . ($errors->has('name_en') ? ' is-invalid' : ''))->required() !!}
                            @if($errors->has('name_en'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('name_en') }}</strong></span>
                            @endif
                        </div>
                        <div class="form-group">
                            {!! Html::label('Description', 'description_en')->class('col-form-label'); !!}
                            <br>
                            {!! Html::textarea('description_en', old('description_en', $instrumentType->description_en ?? null))
                                ->class('form-control' . $errors->has('description_en') ? ' is-invalid' : '')
                                ->id('description_en')->rows(10)->required(); !!}
                            @if ($errors->has('description_en'))
                                <span
                                    class="invalid-feedback"><strong>{{ $errors->first('description_en') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Html::label('Slug', 'slug')->class('col-form-label') !!}
                    {!! Html::text('slug', old('slug', $instrumentType->slug ?? null))->class('form-control' . ($errors->has('slug') ? ' is-invalid' : '')) !!}
                    @if ($errors->has('slug'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-gray card-outline">
            <div class="card-header"><h3 class="card-title">{{ trans('adminlte.image') }}</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label for="photo" class="col-form-label">{{ trans('adminlte.image') }}</label>
                    <div class="file-loading">
                        <input id="photo-input" class="file" type="file" name="photo">
                    </div>
                    @if ($errors->has('photo'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('photo') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ trans('adminlte.' . ($instrumentType ? 'edit' : 'save')) }}</button>
</div>

@section($javaScriptSectionName)
    <script src="{{ asset('vendor/bootstrap-fileinput/js/plugins/piexif.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/bootstrap-fileinput/js/plugins/purify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-fileinput/themes/fa/theme.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-fileinput/js/locales/uz.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-fileinput/js/locales/ru.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-fileinput/js/locales/LANG.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    <script>
        CKEDITOR.replace('description_uz');
        CKEDITOR.replace('description_uz_cy');
        CKEDITOR.replace('description_ru');
        CKEDITOR.replace('description_en');

        let photoInput = $('#photo-input');
        let photoUrl = '{{ $instrumentType && $instrumentType->photo ? $instrumentType->photoOriginal : null }}';

        let send = XMLHttpRequest.prototype.send, token = $('meta[name="csrf-token"]').attr('content');
        XMLHttpRequest.prototype.send = function(data) {
            this.setRequestHeader('X-CSRF-Token', token);
            return send.apply(this, arguments);
        };

        if (photoUrl) {
            photoInput.fileinput({
                initialPreview: [photoUrl],
                initialPreviewAsData: true,
                showUpload: false,
                previewFileType: 'text',
                browseOnZoneClick: true,
                overwriteInitial: true,
                deleteUrl: 'remove-photo',
                maxFileCount: 1,
                allowedFileExtensions: ['jpg', 'jpeg', 'png'],
            });
        } else {
            photoInput.fileinput({
                showUpload: false,
                previewFileType: 'text',
                browseOnZoneClick: true,
                maxFileCount: 1,
                allowedFileExtensions: ['jpg', 'jpeg', 'png'],
            });
        }

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
