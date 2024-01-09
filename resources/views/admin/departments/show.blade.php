<x-admin-page-layout>
    @section('content')
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('dashboard.departments.edit', $department) }}" class="btn btn-primary mr-1">{{ __('adminlte.edit') }}</a>
            <form method="POST" action="{{ route('dashboard.departments.destroy', $department) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('{{ __('adminlte.delete_confirmation_message') }}')">{{ __('adminlte.delete') }}</button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header"><h3 class="card-title">{{ __('adminlte.main') }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped projects">
                            <tbody>
                            <tr><th>ID</th><td>{{ $department->id }}</td></tr>
                            <tr><th>Nomi</th><td>{{ $department->name_uz }}</td></tr>
                            <tr><th>Номи</th><td>{{ $department->name_uz_cy }}</td></tr>
                            <tr><th>Название</th><td>{{ $department->name_ru }}</td></tr>
                            <tr><th>Name</th><td>{{ $department->name_en }}</td></tr>
                            <tr>
                                <th>{{ __('adminlte.organization.name') }}</th>
                                <td><a href="{{ route('dashboard.organizations.show', $department->organization) }}">{{ $department->organization->name }}</a></td>
                            </tr>
                            @if($department->parent)
                                <tr>
                                    <th>{{ __('adminlte.department.parent') }}</th>
                                    <td><a href="{{ route('dashboard.departments.show', $department) }}">{{ $department->parent->name }}</a></td>
                                </tr>
                            @endif
                            <tr><th>Slug</th><td>{{ $department->slug }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-gray card-outline">
                    <div class="card-header"><h3 class="card-title">{{ __('adminlte.other') }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped projects">
                            <tbody>
                            <tr>
                                <th>{{ __('adminlte.created_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $department->createdBy) }}">{{ $department->createdBy->name }}</a></td>
                            </tr>
                            <tr>
                                <th>{{ __('adminlte.updated_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $department->updatedBy) }}">{{ $department->updatedBy->name }}</a></td>
                            </tr>
                            <tr><th>{{ __('adminlte.created_at') }}</th><td>{{ $department->created_at }}</td></tr>
                            <tr><th>{{ __('adminlte.updated_at') }}</th><td>{{ $department->updated_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="branches">
            <div class="card-header card-green with-border">{{ __('adminlte.department.sub') }}</div>
            <div class="card-body">
                <p><a href="{{ route('dashboard.departments.create', ['parent' => $department->id]) }}" class="btn btn-success">{{ __('adminlte.department.add') }}</a></p>

                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td>{{ __('adminlte.name') }}</td>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($branches as $branch)
                        <tr>
                            <td>
                                @for ($i = 0; $i < $branch->depth; $i++) &mdash; @endfor
                                <a href="{{ route('dashboard.departments.show', $branch) }}">{{ $branch->name }}</a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    @endsection
</x-admin-page-layout>
