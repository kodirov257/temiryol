<?php
/* @var $instruments \App\Models\Instrument\InstrumentType[] */
?>

<x-admin-page-layout>
    @section('content')
        <p><a href="{{ route('dashboard.instrument-types.create') }}"
              class="btn btn-success">{{ trans('adminlte.instrument_type.add') }}</a></p>

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
                <td>{{ __('adminlte.image') }}</td>
                <td>Nomi</td>
                <td>Номи</td>
                <td>Название</td>
            </tr>
            </thead>
            <tbody>
            @foreach($instruments as $instrument)
                <tr>
                    <td>
                        @if ($instrument->photo)
                            <a href="{{ $instrument->photoOriginal }}" target="_blank"><img
                                    src="{{ $instrument->photoThumbnail }}" alt="{{ $instrument->name }}"></a>
                        @endif
                    </td>
                    <td><a href="{{ route('dashboard.instrument-types.show', $instrument) }}">{{ $instrument->name_uz }}</a>
                    </td>
                    <td>
                        <a href="{{ route('dashboard.instrument-types.show', $instrument) }}">{{ $instrument->name_uz_cy }}</a>
                    </td>
                    <td><a href="{{ route('dashboard.instrument-types.show', $instrument) }}">{{ $instrument->name_ru }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $instruments->links() }}
    @endsection
</x-admin-page-layout>
