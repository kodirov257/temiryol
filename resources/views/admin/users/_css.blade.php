@if (!config('adminlte.enabled_laravel_mix'))
    @php($cssSectionName = 'css')
@else
    @php($cssSectionName = 'mix_adminlte_css')
    {{--    @section($cssSectionName)--}}
    {{--        <link rel="stylesheet" href="{{ mix('css/fileinput.css', 'build') }}">--}}
    {{--    @endsection--}}
@endif

@section($cssSectionName)
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-fileinput/css/fileinput.css') }}">
@endsection
