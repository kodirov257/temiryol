<x-auth-page-layout auth_type="login">
    @section('adminlte_css_pre')
        <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @stop

    @php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
    @php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
    @php( $password_request_url = View::getSection('password_reset_url') ?? config('adminlte.password_request_url', 'password.email.request') )
    @php( $two_factor_create_url = View::getSection('two_factor_create_url') ?? config('adminlte.two_factor_create_url', 'two-factor-auth.create') )

    @if (config('adminlte.use_route_url', false))
        @php( $login_url = $login_url ? route($login_url) : '' )
        @php( $register_url = $register_url ? route($register_url) : '' )
        @php( $password_request_url = $password_request_url ? route($password_request_url) : '' )
        @php( $two_factor_create_url = $two_factor_create_url ? route($two_factor_create_url) : '' )
    @else
        @php( $login_url = $login_url ? url($login_url) : '' )
        @php( $register_url = $register_url ? url($register_url) : '' )
        @php( $password_request_url = $password_request_url ? url($password_request_url) : '' )
        @php( $two_factor_create_url = $two_factor_create_url ? url($two_factor_create_url) : '' )
    @endif

    @section('auth_header', __('adminlte.login_message'))

    @section('auth_body')
        <form action="{{ $login_url }}" method="post">
            @csrf

            {{-- Email field --}}
            <div class="input-group mb-3">
                <input type="text" name="email_or_username" class="form-control @error('email_or_username') is-invalid @enderror"
                       value="{{ old('email_or_username') }}" placeholder="{{ __('adminlte.email_or_username') }}" autofocus>

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('email_or_username')
                    <span class="invalid-feedback" role="alert">
                        @foreach($errors->get('email_or_username') as $error)
                            <strong>{{ $error }}</strong>
                        @endforeach
                    </span>
                @enderror
            </div>

            {{-- Password field --}}
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="{{ __('adminlte.password') }}">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        @foreach($errors->get('password') as $error)
                            <strong>{{ $error }}</strong>
                        @endforeach
                    </span>
                @enderror
            </div>

            {{-- Login field --}}
            <div class="row">
                <div class="col-7">
                    <div class="icheck-primary" title="{{ __('adminlte.remember_me_hint') }}">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label for="remember">
                            {{ __('adminlte.remember_me') }}
                        </label>
                    </div>
                </div>

                <div class="col-5">
                    <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                        <span class="fas fa-sign-in-alt"></span>
                        {{ __('adminlte.sign_in') }}
                    </button>
                </div>
            </div>

        </form>
    @stop

    @section('auth_footer')
        {{-- Set up OTP link --}}
        @if($two_factor_create_url && config('google2fa.enabled'))
            <p class="my-0">
                <a href="{{ $two_factor_create_url }}">
                    {{ __('adminlte.google2fa_title') }}
                </a>
            </p>
        @endif
        {{-- Password reset link --}}
        @if($password_request_url)
            <p class="my-0">
                <a href="{{ $password_request_url }}">
                    {{ __('adminlte.i_forgot_my_password') }}
                </a>
            </p>
        @endif

        {{-- Register link --}}
        @if($register_url)
            <p class="my-0">
                <a href="{{ $register_url }}">
                    {{ __('adminlte.register_a_new_membership') }}
                </a>
            </p>
        @endif
    @stop
</x-auth-page-layout>
