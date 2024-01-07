<x-admin-page-layout>
    @section('content')
        <p><a href="{{ route('dashboard.users.create') }}" class="btn btn-success">{{ trans('adminlte.user.add') }}</a></p>

        <div class="card mb-4">
            <div class="card-body">
                <form action="?" method="GET">
                    <div class="row">
                        <div class="col-sm-1">
                            <div class="form-group">
                                {!! Html::text('id', request('id'))->class('form-control')->placeholder(trans('adminlte.id')) !!}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                {!! Html::text('name', request('name'))->class('form-control')->placeholder(trans('adminlte.name')) !!}
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                {!! Html::text('email', request('email'))->class('form-control')->placeholder(trans('adminlte.email')) !!}
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                {!! Html::select('status', $statuses, request('status'))->class('form-control')->placeholder(trans('adminlte.status')) !!}
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                {!! Html::select('role', $roles, request('role'))->class('form-control')->placeholder(trans('adminlte.user.role')) !!}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                {!! Html::button(trans('adminlte.search'), 'submit')->class('btn btn-primary') !!}
                                {!! Html::a('?', trans('adminlte.clear'))->class('btn btn-outline-secondary') !!}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <td>ID</td>
                <td>{{ trans('adminlte.user.name') }}</td>
                <td>{{ trans('adminlte.email') }}</td>
                <td>{{ trans('adminlte.user.role') }}</td>
                <td>{{ trans('adminlte.status') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><a href="{{ route('dashboard.users.show', $user) }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roleName() }}</td>
                    <td>
                        @if($user->status === \App\Models\User\User::STATUS_WAIT)
                            <span class="badge badge-secondary">{{ trans('adminlte.user.waiting') }}</span>
                        @elseif($user->status === \App\Models\User\User::STATUS_ACTIVE)
                            <span class="badge badge-primary">{{ trans('adminlte.user.active') }}</span>
                        @elseif($user->status === \App\Models\User\User::STATUS_BLOCKED)
                            <span class="badge badge-danger">{{ trans('adminlte.user.blocked') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    @endsection
</x-admin-page-layout>
