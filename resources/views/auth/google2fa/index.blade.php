<x-auth-page-layout auth_type="login">
    @php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )

    @if (config('adminlte.use_route_url', false))
        @php( $login_url = $login_url ? route($login_url) : '' )
    @else
        @php( $login_url = $login_url ? url($login_url) : '' )
    @endif

    @section('auth_header', __('adminlte.google2fa_title'))

    @section('auth_body')
        <div class="row justify-content-center align-items-center " style="height: 70vh;">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading font-weight-bold">Register</div>
                    <hr>
                    @if($errors->any())
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <strong>{{$errors->first()}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('2fa') }}">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <p>Please enter the  <strong>OTP</strong> generated on your Authenticator App. <br> Ensure you submit the current one because it refreshes every 30 seconds.</p>
                                <label for="one_time_password" class="col-md-12 control-label">One Time Password</label>


                                <div class="col-md-12">
                                    <input id="one_time_password" type="number" class="form-control" name="one_time_password" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 col-md-offset-4 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('adminlte.sign_in') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @stop
</x-auth-page-layout>
