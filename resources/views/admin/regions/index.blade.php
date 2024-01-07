<x-admin-page-layout>
    @section('content')
        <p><a href="{{ route('dashboard.regions.create') }}" class="btn btn-success">{{ trans('adminlte.region.add') }}</a></p>

        <div class="card mb-4">
            <div class="card-body">
                <form action="?" method="GET">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Html::text('name', request('name'))->class('form-control')->placeholder(trans('adminlte.name')) !!}
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
                <td>Nomi</td>
                <td>Название</td>
                <td>Name</td>
                <td>Slug</td>
            </tr>
            </thead>
            <tbody>
            @foreach($regions as $region)
                <tr>
                    <td><a href="{{ route('dashboard.regions.show', $region) }}">{{ $region->name_uz }}</a></td>
                    <td><a href="{{ route('dashboard.regions.show', $region) }}">{{ $region->name_ru }}</a></td>
                    <td><a href="{{ route('dashboard.regions.show', $region) }}">{{ $region->name_en }}</a></td>
                    <td>{{ $region->slug }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $regions->links() }}
    @endsection
</x-admin-page-layout>
