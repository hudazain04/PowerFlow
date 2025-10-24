<?php

namespace App\Jobs;

use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use App\Types\CounterStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Counter;
use App\Models\Action;

class ControlRelay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterId;
    protected $action;
    protected $relay_state;

    public function __construct($counterId, $action, $relay_state)
    {
        $this->counterId = $counterId;
        $this->action = $action;
        $this->relay_state = $relay_state;
    }

    public function handle(): void
    {
        Log::info("🔄 ControlRelay job started", [
            'counterId' => $this->counterId,
            'action' => $this->action,
            'relay_state' => $this->relay_state
        ]);

        try {
            $counter = Counter::where('physical_device_id', $this->counterId)->first();

            if (!$counter) {
                Log::warning("❌ Counter not found for physical_device_id: {$this->counterId}");
                return;
            }


            $counterStatus = $this->relay_state === 'ON' ? CounterStatus::Connect : CounterStatus::DisConnected;



            // Update counter status
            $counter->update([
                'status' => $counterStatus,
            ]);

            Log::info("✅ Counter status updated", [
                'counter_id' => $counter->id,
                'old_status' => $counter->getOriginal('status'),
                'new_status' => $counterStatus
            ]);

            // Find and update the latest pending action for this counter
            $action = Action::where('counter_id', $counter->id)
              ->whereIn('status', [ComplaintStatusTypes::Accepted, ComplaintStatusTypes::Pending])
             ->first();

            if ($action) {
                $oldActionStatus = $action->status;


                $action->update([
                    'status' => ComplaintStatusTypes::Resolved,
                ]);

                Log::info("✅ Action resolved", [
                    'action_id' => $action->id,
                    'action_type' => $action->type,
                    'old_status' => $oldActionStatus,
                    'new_status' => 'resolved'
                ]);

            } else {
                Log::warning("⚠️ No pending action found for counter", [
                    'counter_id' => $counter->id,
                    'counter_physical_id' => $this->counterId
                ]);
            }

            Log::info("🎯 ControlRelay job completed successfully", [
                'counterId' => $this->counterId,
                'action' => $this->action,
                'relay_state' => $this->relay_state
            ]);

        } catch (\Exception $e) {
            Log::error("💥 ControlRelay job failed", [
                'counterId' => $this->counterId,
                'action' => $this->action,
                'relay_state' => $this->relay_state,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw the exception to mark the job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical("💀 ControlRelay job failed completely", [
            'counterId' => $this->counterId,
            'action' => $this->action,
            'relay_state' => $this->relay_state,
            'error' => $exception->getMessage(),
            'failed_at' => now()->toISOString()
        ]);
    }
}
