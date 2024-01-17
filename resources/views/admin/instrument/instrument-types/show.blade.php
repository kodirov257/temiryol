<x-admin-page-layout>
    @section('content')
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('dashboard.instrument-types.edit', $instrumentType) }}" class="btn btn-primary mr-1">{{ __('adminlte.edit') }}</a>
            <form method="POST" action="{{ route('dashboard.instrument-types.destroy', $instrumentType) }}" class="mr-1">
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
                            <tr><th>ID</th><td>{{ $instrumentType->id }}</td></tr>
                            <tr><th>Nomi</th><td>{{ $instrumentType->name_uz }}</td></tr>
                            <tr><th>Номи</th><td>{{ $instrumentType->name_uz_cy }}</td></tr>
                            <tr><th>Название</th><td>{{ $instrumentType->name_ru }}</td></tr>
                            <tr><th>Name</th><td>{{ $instrumentType->name_en }}</td></tr>
                            <tr><th>Tavsifi</th><td>{!! $instrumentType->description_uz !!}</td></tr>
                            <tr><th>Тавсифи</th><td>{!! $instrumentType->description_uz_cy !!}</td></tr>
                            <tr><th>Описание</th><td>{!! $instrumentType->description_ru !!}</td></tr>
                            <tr><th>Description</th><td>{!! $instrumentType->description_en !!}</td></tr>
                            <tr><th>Slug</th><td>{{ $instrumentType->slug }}</td></tr>
                            <tr>
                                <th>{{ __('menu.departments') }} - {{ __('adminlte.quantity') }}</th>
                                <td>
                                    <?php /* @var $department \App\Models\Instrument\DepartmentInstrumentType */ ?>
                                    @foreach($instrumentType->instrumentDepartments()->with('department')->get() as $department)
                                        <a href="{{ route('dashboard.departments.show', $department->department) }}">{{ $department->department->hierarchy }}</a> - {{ $department->quantity }}
                                    @endforeach
                                </td>
                            </tr>
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
                        @if ($instrumentType->photo)
                            <a href="{{ $instrumentType->photoOriginal }}" target="_blank"><img src="{{ $instrumentType->photoThumbnail }}" alt="{{ $instrumentType->photo }}"></a>
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
                                <td><a href="{{ route('dashboard.users.show', $instrumentType->createdBy) }}">{{ $instrumentType->createdBy->name }}</a></td>
                            </tr>
                            <tr>
                                <th>{{ __('adminlte.updated_by') }}</th>
                                <td><a href="{{ route('dashboard.users.show', $instrumentType->updatedBy) }}">{{ $instrumentType->updatedBy->name }}</a></td>
                            </tr>
                            <tr><th>{{ __('adminlte.created_at') }}</th><td>{{ $instrumentType->created_at }}</td></tr>
                            <tr><th>{{ __('adminlte.updated_at') }}</th><td>{{ $instrumentType->updated_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-admin-page-layout>
