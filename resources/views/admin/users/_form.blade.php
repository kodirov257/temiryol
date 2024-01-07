@include('admin.users._css')

<div class="row">
    <div class="col-md-12">
        <div class="card card-gray card-outline">
            <div class="card-header"><h3 class="card-title"></h3></div>
            <div class="card-body">
                <div class="form-group">
                    {!! Html::label(trans('adminlte.user.name'), 'name')->class('col-form-label') !!}
                    {!! Html::text('name', old('name', $user->name ?? null))->class('form-control' . ($errors->has('name') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('name'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label(trans('adminlte.email'), 'email')->class('col-form-label') !!}
                    {!! Html::email('email', old('email', $user->email ?? null))->class('form-control' . ($errors->has('email') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('email'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label(trans('adminlte.user.role'), 'role')->class('col-form-label') !!}
                    {!! Html::select('role', $roles, old('role', $user->role ?? null))->class('form-control' . ($errors->has('role') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('role'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('role') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label(trans('adminlte.status'), 'status')->class('col-form-label') !!}
                    {!! Html::select('status', $statuses, old('status', $user->status ?? null))->class('form-control' . ($errors->has('status') ? ' is-invalid' : ''))->required() !!}
                    @if($errors->has('status'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('status') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label(trans('adminlte.password'), 'password')->class('col-form-label') !!}
                    {!! Html::password('password')->class('form-control' . ($errors->has('password') ? ' is-invalid' : '')) !!}
                    @if($errors->has('name_ru'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-gray card-outline">
            <div class="card-header"><h3 class="card-title">{{ trans('adminlte.user.profile') }}</h3></div>
            <div class="card-body">
                <div class="form-group">
                    {!! Html::label(trans('adminlte.first_name'), 'first_name')->class('col-form-label'); !!}
                    {!! Html::text('first_name', old('first_name', $user ? ($user->profile->first_name ?? null) : null))
                            ->class('form-control' . ($errors->has('first_name') ? ' is-invalid' : '')) !!}
                    @if ($errors->has('first_name'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('first_name') }}</strong></span>
                    @endif
                </div>

                <div class="form-group">
                    {!! Html::label(trans('adminlte.last_name'), 'last_name')->class('col-form-label'); !!}
                    {!! Html::text('last_name', old('last_name', $user ? ($user->profile->last_name ?? null) : null))
                            ->class('form-control' . ($errors->has('last_name') ? ' is-invalid' : '')) !!}
                    @if ($errors->has('last_name'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('last_name') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Html::label(trans('adminlte.birth_date'), 'birth_date')->class('col-form-label'); !!}
                    {!! Html::date('birth_date', old('birth_date', $user ? ($user->profile->birth_date ?? null) : null))
                            ->class('form-control' . ($errors->has('birth_date') ? ' is-invalid' : '')) !!}
                    @if ($errors->has('birth_date'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('birth_date') }}</strong></span>
                    @endif
                </div>

                <div class="form-group">
                    {!! Html::label(trans('adminlte.gender'), 'gender')->class('col-form-label'); !!}
                    {!! Html::select('gender', $genders, old('gender', $user ? ($user->profile->gender ?? null) : null))
                            ->class('form-control' . ($errors->has('gender') ? ' is-invalid' : '')) !!}
                    @if ($errors->has('gender'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('gender') }}</strong></span>
                    @endif
                </div>

                <div class="form-group">
                    {!! Html::label(trans('adminlte.address'), 'address')->class('col-form-label'); !!}
                    {!! Html::text('address', old('address', $user ? ($user->profile->address ?? null) : null))
                            ->class('form-control' . ($errors->has('address') ? ' is-invalid' : '')) !!}
                    @if ($errors->has('address'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('address') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">{{ trans('adminlte.files') }}</h3></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avatar" class="col-form-label">{{ trans('adminlte.image') }}</label>
                            <div class="file-loading">
                                <input id="avatar-input" class="file" type="file" name="avatar"
                                       accept=".jpg,.jpeg,.png,.gif">
                            </div>
                            @if ($errors->has('avatar'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('avatar') }}</strong></span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<input name="userId" type="hidden" value="{{ $user->id ?? null }}">

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ trans('adminlte.' . ($user ? 'edit' : 'save')) }}</button>
</div>

@include('admin.users._js')
