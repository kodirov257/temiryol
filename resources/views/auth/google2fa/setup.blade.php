<x-auth-page-layout auth_type="login">
    @php( $setup_completion_url = View::getSection('setup_completion_url') ?? config('adminlte.setup_completion_url', 'two-factor-auth.complete') )

    @if (config('adminlte.use_route_url', false))
        @php( $setup_completion_url = $setup_completion_url ? route($setup_completion_url) : '' )
    @else
        @php( $setup_completion_url = $setup_completion_url ? url($setup_completion_url) : '' )
    @endif

    @section('auth_header', __('adminlte.google2fa_title'))

    @section('auth_body')
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card card-default">
                    <h4 class="card-heading text-center mt-4">@lang('adminlte.setup_google2fa')</h4>

                    <div class="card-body" style="text-align: center;">
                        <p>@lang('adminlte.google2fa_message1') <strong>{{ $secret }}</strong></p>
                        <div><img src="{{ $QR_Image }}" alt="QRCode image"></div>
                        <p>@lang('adminlte.google2fa_message2')</p>
                    </div>
                    <div>
                        <form id="logout-form" action="{{ $setup_completion_url }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="email" value="{{ $email }}">
                            <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                                <span class="fas fa-user-plus"></span>
                                {{ __('adminlte.setup_complete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @stop
</x-auth-page-layout>
