<?php

namespace App\Console\Commands;

use App\Models\Instrument\Operation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredInstruments extends Command
{
    protected $signature = 'operation:expired';

    protected $description = 'Update operation as expired which deadline has come.';

    public function handle(): void
    {
        $this->line('<fg=cyan>Expired operations.</>');

        $operations = Operation::active()->whereNotIn('status', [Operation::STATUS_CLOSED, Operation::STATUS_EXPIRED])
            ->where('deadline', '<=', Carbon::now())->get();
        try {
            foreach ($operations as $operation) {
                $operation->updateOrFail([
                    'status' => Operation::STATUS_EXPIRED,
                ]);
            }
            $this->line('<fg=blue>Successfully updated operations.</>');
        } catch (\Exception|\Throwable $e) {
            $this->line('<fg=red>Error occurred: ' . $e->getMessage() . '</>');
        }
    }
}
