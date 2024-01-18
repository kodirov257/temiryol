<?php
/* @var $instruments \App\Models\Instrument\Instrument[] */
?>

<x-admin-page-layout>
    @section('content')
        <p><a href="{{ route('dashboard.department-instrument-types.instruments.create', $departmentInstrumentType) }}"
              class="btn btn-success">{{ trans('adminlte.instrument.add') }}</a></p>

        <div class="card mb-4">
            <div class="card-body">
                <form action="?" method="GET">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Html::text('serial', request('serial'))->class('form-control')->placeholder(trans('adminlte.instrument.serial')) !!}
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
                <td>{{ __('adminlte.instrument.serial') }}</td>
                <td>{{ __('adminlte.status') }}</td>
                <td>{{ __('adminlte.notes') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($instruments as $instrument)
                <tr>
                    <td>
                        <a href="{{ route('dashboard.department-instrument-types.instruments.show', ['departmentInstrumentType' => $departmentInstrumentType, 'instrument' => $instrument]) }}">
                            {{ $instrument->serial }}
                        </a>
                    </td>
                    <td>
                        {!! $instrument->statusLabel() !!}
                    </td>
                    <td>{{ $instrument->notes }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $instruments->links() }}
    @endsection
</x-admin-page-layout>
