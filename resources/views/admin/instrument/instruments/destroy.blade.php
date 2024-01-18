@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
    @php($javaScriptSectionName = 'js')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    @php($javaScriptSectionName = 'mix_adminlte_js')
@endif

<x-admin-page-layout>
    @include('layouts.admin.flash')

    @section('content')
        <form action="{{ route('dashboard.department-instrument-types.instruments.destroy', ['departmentInstrumentType' => $departmentInstrumentType, 'instrument' => $instrument]) }}" method="POST">
            @csrf
            @method('DELETE')

            {{--            @include('partials.admin._nav')--}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-gray card-outline">
                        <div class="card-header"><h3 class="card-title"></h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                {!! Html::label(__('adminlte.notes'), 'notes')->class('col-form-label'); !!}
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

            <div class="form-group">
                <button class="btn btn-danger" onclick="return confirm('{{ __('adminlte.delete_confirmation_message') }}')">{{ __('adminlte.delete') }}</button>
            </div>
        </form>
    @endsection

    @section($javaScriptSectionName)
        <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('notes');
        </script>
    @endsection
</x-admin-page-layout>
