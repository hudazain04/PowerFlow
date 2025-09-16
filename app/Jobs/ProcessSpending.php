<?php
namespace App\Jobs;

use App\Models\Spending;
use App\Models\Counter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSpending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterId;
    protected $energyWh;
    protected $created_at;

    public function __construct($counterId, $energyWh,$created_at)
    {
        $this->counterId = $counterId;
        $this->energyWh = $energyWh;
        $this->created_at=$created_at;
    }

    public function handle(): void
    {
        $counter = Counter::where('physical_device_id', $this->counterId)->first();

        if ($counter) {
            Spending::create([
                'counter_id' => $counter->id,
                'consume' => $this->energyWh,
                'date' => $this->created_at,
            ]);
        }
    }
}
