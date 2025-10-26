<?php

namespace App\Console\Commands;

use App\Models\ConsumptionStatistic;
use App\Models\Counter;
use Illuminate\Console\Command;

class CalculateConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'calculate consumption statistics for each counter';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $counters = Counter::all();

        foreach ($counters as $counter) {
            $spendings = $counter->spendings()
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->pluck('amount');
            if ($spendings->count() < 4) {
                continue;
            }
            $increments = [];
            for ($i = 1; $i < $spendings->count(); $i++) {
                $increments[] = $spendings[$i] - $spendings[$i - 1];
            }
            sort($increments);


            [$q1, $q3, $iqr, $upperBound] = $this->calculateIqr($increments);

            ConsumptionStatistic::updateOrCreate(
                ['counter_id' => $counter->id],
                [
                    'q1'          => $q1,
                    'q3'          => $q3,
                    'iqr'         => $iqr,
                    'upper_bound' => $upperBound,
                ]
            );
        }
    }

    /**
     * حساب Q1, Q3, IQR, Upper Bound
     */
    private function calculateIqr(array $data): array
    {

        $q1 = $this->percentile($data, 25);
        $q3 = $this->percentile($data, 75);

        $iqr = $q3 - $q1;
        $upperBound = $q3 + 1.5 * $iqr;

        return [$q1, $q3, $iqr, $upperBound];
    }

    /**
     * حساب الـ Percentile
     */
    private function percentile(array $data, $percentile)
    {
        $index = ($percentile / 100) * (count($data) - 1);
        $floor = floor($index);
        $ceil = ceil($index);

        if ($floor == $ceil) return $data[$index];
        return $data[$floor] + ($index - $floor) * ($data[$ceil] - $data[$floor]);
    }

}
