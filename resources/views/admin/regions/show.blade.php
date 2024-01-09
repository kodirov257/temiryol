<x-admin-page-layout>
    @section('content')
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('dashboard.regions.edit', $region) }}" class="btn btn-primary mr-1">{{ __('adminlte.edit') }}</a>
            <form method="POST" action="{{ route('dashboard.regions.destroy', $region) }}" class="mr-1">
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
                            <tr><th>ID</th><td>{{ $region->id }}</td></tr>
                            <tr><th>Nomi</th><td>{{ $region->name_uz }}</td></tr>
                            <tr><th>Номи</th><td>{{ $region->name_uz_cy }}</td></tr>
                            <tr><th>Название</th><td>{{ $region->name_ru }}</td></tr>
                            <tr><th>Name</th><td>{{ $region->name_en }}</td></tr>
                            <tr><th>{{ __('adminlte.type') }}</th><td>{{ $region->typeName() }}</td></tr>
                            @if($region->parent)
                                <tr>
                                    <th>{{ __('adminlte.region.parent') }}</th>
                                    <td><a href="{{ route('dashboard.regions.show', $region) }}">{{ $region->parent->name_en }}</a></td>
                                </tr>
                            @endif
                            <tr><th>Slug</th><td>{{ $region->slug }}</td></tr>
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
                                <td><a href="{{ route('dashboard.users.show', $region->createdBy) }}">{{ $region->createdBy->name }}</a></td>
                            </tr>
                            <tr>
                                <th>{{ __('adminlte.updated_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $region->updatedBy) }}">{{ $region->updatedBy->name }}</a></td>
                            </tr>
                            <tr><th>{{ __('adminlte.created_at') }}</th><td>{{ $region->created_at }}</td></tr>
                            <tr><th>{{ __('adminlte.updated_at') }}</th><td>{{ $region->updated_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="states">
            <div class="card-header card-green with-border">{{ __('menu.regions') }}</div>
            <div class="card-body">
                <p><a href="{{ route('dashboard.regions.create', ['parent' => $region->id]) }}" class="btn btn-success">{{ __('adminlte.region.add') }}</a></p>

                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Nomi</th>
                        <th>Название</th>
                        <th>Name</th>
                        <th>{{ __('adminlte.type') }}</th>
                        <th>Slug</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($region->children as $child)
                        <tr>
                            <td><a href="{{ route('dashboard.regions.show', $child) }}">{{ $child->name_uz }}</a></td>
                            <td><a href="{{ route('dashboard.regions.show', $child) }}">{{ $child->name_ru }}</a></td>
                            <td><a href="{{ route('dashboard.regions.show', $child) }}">{{ $child->name_en }}</a></td>
                            <td>{{ $child->typeName() }}</td>
                            <td>{{ $child->slug }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    @endsection
</x-admin-page-layout>
