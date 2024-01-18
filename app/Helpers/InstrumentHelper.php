<?php

namespace App\Helpers;

use App\Models\Instrument\Instrument;

class InstrumentHelper
{
    public static function statusLabel(int $status): string
    {
        return match ($status) {
            Instrument::STATUS_UNAVAILABLE => '<span class="badge badge-warning">' . __('adminlte.instrument.unavailable') . '</span>',
            Instrument::STATUS_BROKEN => '<span class="badge badge-danger">' . __('adminlte.instrument.broken') . '</span>',
            Instrument::STATUS_IN_USE => '<span class="badge badge-secondary">' . __('adminlte.instrument.in_use') . '</span>',
            Instrument::STATUS_NOT_RETURNED => '<span class="badge badge-dark">' . __('adminlte.instrument.not_returned') . '</span>',
            Instrument::STATUS_AVAILABLE => '<span class="badge badge-success">' . __('adminlte.instrument.available') . '</span>',
            Instrument::STATUS_REPAIRED => '<span class="badge badge-primary">' . __('adminlte.instrument.repaired') . '</span>',
            default => '<span class="badge badge-warning">Default</span>',
        };
    }
}
