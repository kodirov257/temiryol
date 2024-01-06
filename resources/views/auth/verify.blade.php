<x-auth-page-layout auth-type="login">
    @section('auth_header', __('adminlte.verify_message'))

    @section('auth_body')

        @if(session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('adminlte.verify_email_sent') }}
            </div>
        @endif

        {{ __('adminlte.verify_check_your_email') }}
        {{ __('adminlte.verify_if_not_recieved') }},

        <form class="d-inline" method="POST" action="{{ route('verification.email.send') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $user->email }}">
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                {{ __('adminlte.verify_request_another') }}
            </button>.
        </form>

    @stop
</x-auth-page-layout>
