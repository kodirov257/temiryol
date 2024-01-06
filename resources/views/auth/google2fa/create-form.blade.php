<x-auth-page-layout auth_type="login">
    @section('adminlte_css_pre')
        <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @stop

    @php( $two_factor_store_url = View::getSection('two_factor_store_url') ?? config('adminlte.two_factor_store_url', 'two-factor-auth.create') )

    @if (config('adminlte.use_route_url', false))
        @php( $two_factor_store_url = $two_factor_store_url ? route($two_factor_store_url) : '' )
    @else
        @php( $two_factor_store_url = $two_factor_store_url ? url($two_factor_store_url) : '' )
    @endif

    @section('auth_header', trans('adminlte.google2fa_title'))

    @section('auth_body')
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $two_factor_store_url }}" method="post">
            @csrf

            {{-- Email field --}}
            <div class="input-group mb-3">
                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="{{ __('adminlte.email') }}" autofocus>

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('email')
                <span class="invalid-feedback" role="alert">
                        @foreach($errors->get('email') as $error)
                        <strong>{{ $error }}</strong>
                    @endforeach
                    </span>
                @enderror
            </div>



            {{-- Setup field --}}
            <div class="row">
                <div class="col-5">
                    <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                        <span class="fas fa-sign-in-alt"></span>
                        {{ __('adminlte.setup') }}
                    </button>
                </div>
            </div>

        </form>
    @stop
</x-auth-page-layout>
