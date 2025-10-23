<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Jobs\ControleRelay;

class RelayQueueManage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume-relay
                            {--timeout=30 : Connection timeout in seconds}
                            {--max-messages=0 : Maximum number of messages to process (0 for unlimited)}
                            {--sleep=1 : Sleep time between messages in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume relay commands from RabbitMQ queue';

    protected $processedMessages = 0;
    protected $maxMessages = 0;
    protected $shouldStop = false;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Relay Commands Consumer...');
        $this->maxMessages = (int) $this->option('max-messages');

        // Setup signal handlers for graceful shutdown
//        pcntl_async_signals(true);
//        pcntl_signal(SIGTERM, [$this, 'signalHandler']);
//        pcntl_signal(SIGINT, [$this, 'signalHandler']);

        try {
            $connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.hosts.0.host', 'localhost'),
                config('queue.connections.rabbitmq.hosts.0.port', 5672),
                config('queue.connections.rabbitmq.hosts.0.user', 'guest'),
                config('queue.connections.rabbitmq.hosts.0.password', 'guest'),
                config('queue.connections.rabbitmq.hosts.0.vhost', '/')
            );

            $channel = $connection->channel();

            $queue = 'relay_commands';

            $channel->queue_declare($queue, false, true, false, false);

            $this->info("✅ Connected to RabbitMQ");
            $this->info("📥 Queue: {$queue}");
            $this->info("⏰ Timeout: " . $this->option('timeout') . "s");
            $this->info("🔢 Max Messages: " . ($this->maxMessages > 0 ? $this->maxMessages : 'Unlimited'));
            $this->info("⏹️  Press CTRL+C to exit gracefully\n");

            $callback = function ($msg) {
                try {
                    $data = json_decode($msg->body, true);

                    $this->info("📨 [] Received relay command - Counter: {$data['counterId']}, Action: " . strtoupper($data['action']));

                    // Validate required fields
                    if (!isset($data['counterId']) || !isset($data['action'])) {
                        $this->error("❌ Invalid relay command format - missing required fields");
                        $msg->ack();
                        return;
                    }

                    if (!in_array($data['action'], ['connect', 'disconnect'])) {
                        $this->error("❌ Invalid action: {$data['action']}");
                        $msg->ack();
                        return;
                    }

                    // Dispatch job to process the relay command
                    ControleRelay::dispatch(
                        $data['counterId'],
                        $data['action'],
                        $data['timestamp'] ?? now()->toISOString(),
                        3, // maxRetries
                        10 // timeout
                    );

                    $this->info("✅ Job dispatched for counter: {$data['counterId']} - Action: " . strtoupper($data['action']));

                    // Acknowledge the message
                    $msg->ack();

                    $this->processedMessages++;

                    // Check if we've reached the max messages limit
                    if ($this->maxMessages > 0 && $this->processedMessages >= $this->maxMessages) {
                        $this->info("🎯 Reached maximum message limit ({$this->maxMessages}), stopping...");
                        $this->shouldStop = true;
                    }

                    // Sleep between messages if specified
                    $sleepTime = (int) $this->option('sleep');
                    if ($sleepTime > 0) {
                        sleep($sleepTime);
                    }

                } catch (\Exception $e) {
                    $this->error("❌ Error processing relay command: " . $e->getMessage());
                    // You might want to handle the error differently (e.g., send to dead letter queue)
                    $msg->ack(); // Still acknowledge to avoid reprocessing broken messages
                }
            };

            // Set QoS to only prefetch one message at a time
            $channel->basic_qos(null, 1, null);

            $channel->basic_consume($queue, '', false, false, false, false, $callback);

            // Main consumption loop with timeout
            $timeout = (int) $this->option('timeout');
            while ($channel->is_consuming() && !$this->shouldStop) {
                try {
                    $channel->wait(null, false, $timeout);
                } catch (\PhpAmqpLib\Exception\AMQPTimeoutException $e) {
                    // Timeout is normal, just continue
                    continue;
                } catch (\Exception $e) {
                    $this->error("❌ Error in consumption loop: " . $e->getMessage());
                    break;
                }
            }

            $this->info("\n🛑 Gracefully shutting down...");
            $this->info("📊 Total messages processed: {$this->processedMessages}");

            $channel->close();
            $connection->close();

            $this->info("✅ Consumer stopped gracefully");

        } catch (\Exception $e) {
            $this->error("💥 Fatal error: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Signal handler for graceful shutdown
     */
    public function signalHandler($signal)
    {
        $this->info("\n🛑 Received shutdown signal, stopping gracefully...");
        $this->shouldStop = true;
    }
}
