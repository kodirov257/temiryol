<x-admin-page-layout>
    @section('content')
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('dashboard.instruments.edit', $instrument) }}" class="btn btn-primary mr-1">{{ __('adminlte.edit') }}</a>
            <form method="POST" action="{{ route('dashboard.instruments.destroy', $instrument) }}" class="mr-1">
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
                            <tr><th>ID</th><td>{{ $instrument->id }}</td></tr>
                            <tr><th>Nomi</th><td>{{ $instrument->name_uz }}</td></tr>
                            <tr><th>Номи</th><td>{{ $instrument->name_uz_cy }}</td></tr>
                            <tr><th>Название</th><td>{{ $instrument->name_ru }}</td></tr>
                            <tr><th>Name</th><td>{{ $instrument->name_en }}</td></tr>
                            <tr><th>Tavsifi</th><td>{!! $instrument->description_uz !!}</td></tr>
                            <tr><th>Тавсифи</th><td>{!! $instrument->description_uz_cy !!}</td></tr>
                            <tr><th>Описание</th><td>{!! $instrument->description_ru !!}</td></tr>
                            <tr><th>Description</th><td>{!! $instrument->description_en !!}</td></tr>
                            <tr><th>{{ __('adminlte.quantity') }}</th><td>{{ $instrument->quantity }}</td></tr>
                            <tr><th>{{ __('adminlte.weight') }}</th><td>{{ $instrument->weight }}</td></tr>
                            <tr>
                                <th>{{ __('adminlte.department.name') }}</th>
                                <td><a href="{{ route('dashboard.departments.show', $instrument->department) }}">{{ $instrument->department->hierarchy }}</a></td>
                            </tr>
                            <tr><th>Slug</th><td>{{ $instrument->slug }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-gray card-outline">
                    <div class="card-header"><h3 class="card-title">@lang('adminlte.image')</h3></div>
                    <div class="card-body">
                        @if ($instrument->photo)
                            <a href="{{ $instrument->photoOriginal }}" target="_blank"><img src="{{ $instrument->photoThumbnail }}" alt="{{ $instrument->photo }}"></a>
                        @endif
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
                                <td><a href="{{ route('dashboard.users.show', $instrument->createdBy) }}">{{ $instrument->createdBy->name }}</a></td>
                            </tr>
                            <tr>
                                <th>{{ __('adminlte.updated_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $instrument->updatedBy) }}">{{ $instrument->updatedBy->name }}</a></td>
                            </tr>
                            <tr><th>{{ __('adminlte.created_at') }}</th><td>{{ $instrument->created_at }}</td></tr>
                            <tr><th>{{ __('adminlte.updated_at') }}</th><td>{{ $instrument->updated_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-admin-page-layout>
