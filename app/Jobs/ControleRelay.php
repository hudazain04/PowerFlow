<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ControleRelay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $counterId;
    public $action;
    public $timestamp;
    public $maxRetries;
    public $timeout;

    /**
     * Create a new job instance.
     */
    public function __construct($counterId, $action, $timestamp, $maxRetries = 3, $timeout = 10)
    {
        $this->counterId = $counterId;
        $this->action = $action; // 'connect' or 'disconnect'
        $this->timestamp = $timestamp;
        $this->maxRetries = $maxRetries;
        $this->timeout = $timeout;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("🔄 Processing relay command from RabbitMQ", [
            'counterId' => $this->counterId,
            'action' => $this->action,
            'timestamp' => $this->timestamp,
            'attempt' => $this->attempts()
        ]);

        try {
            // Call Node.js API to execute the relay command
            $response = Http::timeout($this->timeout)
                ->post("http://192.168.67.31:3000/relay/{$this->action}/{$this->counterId}");

            $result = $response->json();

            if ($response->successful()) {
                if ($result['status'] === 'success' && $result['confirmed'] === true) {
                    Log::info("✅ Relay command executed successfully", [
                        'counterId' => $this->counterId,
                        'action' => $this->action,
                        'relayState' => $result['relayState'],
                        'queueId' => $result['queueId'] ?? null,
                        'message' => $result['message']
                    ]);

                    // Update database or trigger events here
                    $this->updateCounterStatus($this->counterId, $result['relayState']);

                    // Emit Socket event if needed
                    $this->emitRelayStatusUpdate($this->counterId, $result['relayState']);

                } else {
                    $error = $result['error'] ?? 'Command not confirmed by ESP32';
                    Log::error("❌ Relay command not confirmed", [
                        'counterId' => $this->counterId,
                        'action' => $this->action,
                        'api_status' => $result['status'],
                        'confirmed' => $result['confirmed'] ?? false,
                        'error' => $error
                    ]);

                    throw new \Exception("Command not confirmed: " . $error);
                }
            } else {
                Log::error("❌ HTTP request failed", [
                    'counterId' => $this->counterId,
                    'action' => $this->action,
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]);

                throw new \Exception("HTTP Error: " . $response->status() . " - " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("❌ ProcessRelayCommand job failed", [
                'counterId' => $this->counterId,
                'action' => $this->action,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            if ($this->attempts() < $this->maxRetries) {
                // Retry with exponential backoff
                $retryDelay = $this->attempts() * 10; // 10, 20, 30 seconds
                $this->release(now()->addSeconds($retryDelay));
                return;
            }

            // Max retries reached
            $this->handlePermanentFailure($e->getMessage());
            throw $e;
        }
    }

    /**
     * Update counter status in database
     */
    protected function updateCounterStatus($counterId, $relayState)
    {
        try {
            $counter = \App\Models\Counter::where('physical_device_id', $counterId)->first();

            if ($counter) {
                $relayStateValue = $relayState === 'ON' ? 1 : 0;
                $counter->update([
                    'relay_state' => $relayStateValue,
                    'last_activity' => now(),
                ]);

                Log::info("📊 Counter status updated in database", [
                    'counterId' => $counterId,
                    'relayState' => $relayState,
                    'database_value' => $relayStateValue
                ]);
            } else {
                Log::warning("⚠️ Counter not found in database", [
                    'counterId' => $counterId
                ]);
            }
        } catch (\Exception $e) {
            Log::warning("⚠️ Could not update counter status in database", [
                'counterId' => $counterId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Emit relay status update via Socket.IO or other real-time service
     */
    protected function emitRelayStatusUpdate($counterId, $relayState)
    {
        try {
            // You can implement Socket.IO client here if needed
            // Or use Laravel WebSockets, Pusher, etc.
            Log::info("📡 Relay status update ready for real-time broadcast", [
                'counterId' => $counterId,
                'relayState' => $relayState
            ]);

            // Example: Broadcast via Laravel Echo
            // broadcast(new RelayStatusUpdated($counterId, $relayState));

        } catch (\Exception $e) {
            Log::warning("⚠️ Could not emit real-time update", [
                'counterId' => $counterId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle permanent failure
     */
    protected function handlePermanentFailure($error)
    {
        Log::error("💥 Relay command failed permanently after retries", [
            'counterId' => $this->counterId,
            'action' => $this->action,
            'error' => $error,
            'max_retries' => $this->maxRetries
        ]);

        // You can notify admins here
        // Example: Send email, Slack notification, etc.
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical("💀 ProcessRelayCommand job failed completely", [
            'counterId' => $this->counterId,
            'action' => $this->action,
            'error' => $exception->getMessage(),
            'failed_at' => now()->toISOString()
        ]);

        // Mark the command as failed in database
        $this->markCommandAsFailed($this->counterId, $this->action, $exception->getMessage());
    }

    /**
     * Mark command as failed in database
     */
    protected function markCommandAsFailed($counterId, $action, $error)
    {
        try {
            // You can create a failed_commands table or update counters table
            Log::info("📝 Marking relay command as failed in system", [
                'counterId' => $counterId,
                'action' => $action,
                'error' => $error
            ]);
        } catch (\Exception $e) {
            Log::warning("⚠️ Could not mark command as failed", [
                'counterId' => $counterId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
