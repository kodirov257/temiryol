<?php

namespace App\Console\Commands;

use App\Models\Instrument\Operation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiringInstruments extends Command
{
    protected $signature = 'operation:expiring';

    protected $description = 'Update operation as expiring which deadline coming is soon.';

    public function handle(): void
    {
        echo 'Expiring operations.' . PHP_EOL;
        $operations = Operation::active()->whereNotIn('status', [Operation::STATUS_CLOSED, Operation::STATUS_EXPIRING, Operation::STATUS_EXPIRED])
            ->where('deadline', '<=', Carbon::now()->addHour())->get();

        try {
            foreach ($operations as $operation) {
                $operation->updateOrFail([
                    'status' => Operation::STATUS_EXPIRING,
                ]);
            }
            $this->line('<fg=blue>Successfully updated operations.</>');
        } catch (\Exception|\Throwable $e) {
            $this->line('<fg=red>Error occurred: ' . $e->getMessage() . '</>');
        }
    }
}
