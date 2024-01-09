<x-admin-page-layout>
    @section('content')
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('dashboard.organizations.edit', $organization) }}" class="btn btn-primary mr-1">{{ __('adminlte.edit') }}</a>
            <form method="POST" action="{{ route('dashboard.organizations.destroy', $organization) }}" class="mr-1">
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
                            <tr><th>ID</th><td>{{ $organization->id }}</td></tr>
                            <tr><th>Nomi</th><td>{{ $organization->name_uz }}</td></tr>
                            <tr><th>Номи</th><td>{{ $organization->name_uz_cy }}</td></tr>
                            <tr><th>Название</th><td>{{ $organization->name_ru }}</td></tr>
                            <tr><th>Name</th><td>{{ $organization->name_en }}</td></tr>
                            <tr>
                                <th>{{ __('adminlte.region.name') }}</th>
                                <td><a href="{{ route('dashboard.regions.show', $organization->region) }}">{{ $organization->region->place }}</a></td>
                            </tr>
                            <tr><th>{{ __('adminlte.type') }}</th><td>{{ $organization->typeName() }}</td></tr>
                            @if($organization->parent)
                                <tr>
                                    <th>{{ __('adminlte.organization.parent') }}</th>
                                    <td><a href="{{ route('dashboard.organizations.show', $organization) }}">{{ $organization->parent->name }}</a></td>
                                </tr>
                            @endif
                            <tr><th>Slug</th><td>{{ $organization->slug }}</td></tr>
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
                                <td><a href="{{ route('dashboard.users.show', $organization->createdBy) }}">{{ $organization->createdBy->name }}</a></td>
                            </tr>
                            <tr>
                                <th>{{ __('adminlte.updated_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $organization->updatedBy) }}">{{ $organization->updatedBy->name }}</a></td>
                            </tr>
                            <tr><th>{{ __('adminlte.created_at') }}</th><td>{{ $organization->created_at }}</td></tr>
                            <tr><th>{{ __('adminlte.updated_at') }}</th><td>{{ $organization->updated_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="states">
            <div class="card-header card-green with-border">{{ __('adminlte.organization.branches') }}</div>
            <div class="card-body">
                <p><a href="{{ route('dashboard.organizations.create', ['parent' => $organization->id]) }}" class="btn btn-success">{{ __('adminlte.organization.add') }}</a></p>

                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td>{{ __('adminlte.organization.name') }}</td>
                        <td>{{ __('adminlte.type') }}</td>
                        <td>{{ __('adminlte.region.name') }}</td>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($branches as $branch)
                        <tr>
                            <td>
                                @for ($i = 0; $i < $branch->depth; $i++) &mdash; @endfor
                                <a href="{{ route('dashboard.organizations.show', $branch) }}">{{ $branch->name }}</a>
                            </td>
                            <td>{{ $branch->typeName() }}</td>
                            <td><a href="{{ route('dashboard.regions.show', $branch->region) }}">{{ $branch->region->place }}</a></td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    @endsection
</x-admin-page-layout>
