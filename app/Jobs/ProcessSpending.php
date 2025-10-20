<?php
namespace App\Jobs;

use App\Models\Spending;
use App\Models\Counter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
//        $counter = Counter::where('physical_device_id', $this->counterId)->first();
//
//        if ($counter) {
//            Spending::create([
//                'counter_id' => $counter->id,
//                'consume' => $this->energyWh,
//                'date' => $this->created_at,
//            ]);
//            Log::info("Spending recorded for counter {$this->counterId}: {$this->energyWh} Wh");
//        } else {
//            Log::warning("Counter not found for physical_device_id: {$this->counterId}");
//        }
//
//    }
//}
        \Log::info("ProcessSpending job started", [
            'counterId' => $this->counterId,
            'energyWh' => $this->energyWh,
            'created_at' => $this->created_at
        ]);

        $counter = Counter::where('physical_device_id', $this->counterId)->first();

        if ($counter) {
            \Log::info("Counter found", ['counter_id' => $counter->id]);

            $spending = Spending::create([
                'counter_id' => $counter->id,
                'consume' => $this->energyWh,
                'date' => $this->created_at,
            ]);

            \Log::info("Spending record created", [
                'spending_id' => $spending->id,
                'counter_id' => $counter->id,
                'consume' => $this->energyWh
            ]);

        } else {
            \Log::warning("Counter not found for physical_device_id: {$this->counterId}");
        }
    }
}
