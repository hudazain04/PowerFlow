<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Jobs\ProcessSpending;

class ConsumeSpendings extends Command
{
    protected $signature = 'rabbitmq:consume-spendings';
    protected $description = 'Consume spending messages from RabbitMQ';

    public function handle()
    {
        $this->info('Starting RabbitMQ consumer...');

        try {
            // Test RabbitMQ connection first
            $this->info('Connecting to RabbitMQ...');

            $connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.hosts.0.host', 'localhost'),
                config('queue.connections.rabbitmq.hosts.0.port', 5672),
                config('queue.connections.rabbitmq.hosts.0.user', 'guest'),
                config('queue.connections.rabbitmq.hosts.0.password', 'guest'),
                config('queue.connections.rabbitmq.hosts.0.vhost', '/'),
                false, // insist
                'AMQPLAIN', // login_method
                null, // login_response
                'en_US', // locale
                3.0, // connection_timeout
                3.0, // read_write_timeout
                null, // context
                false, // keepalive
                60 // heartbeat
            );

            $this->info('Connected to RabbitMQ successfully');

            $channel = $connection->channel();

            $queue = 'default';
            $channel->queue_declare($queue, false, true, false, false);

            $this->info("Waiting for messages in queue: {$queue}");

            $callback = function ($msg) {
                try {
                    $data = json_decode($msg->body, true, 512, JSON_THROW_ON_ERROR);

                    $this->info("Received message for counter: " . ($data['counterId'] ?? 'unknown'));

                    // Validate required fields
                    if (!isset($data['counterId'], $data['energyWh'], $data['created_at'])) {
                        $this->error("Invalid message format: " . $msg->body);
                        $msg->ack();
                        return;
                    }

                    // Dispatch the job
                    ProcessSpending::dispatch(
                        $data['counterId'],
                        $data['energyWh'],
                        $data['created_at']
                    );

                    $this->info("Job dispatched for counter: " . $data['counterId']);
                    $msg->ack();

                } catch (\JsonException $e) {
                    $this->error("JSON decode error: " . $e->getMessage());
                    $msg->ack();
                } catch (\Exception $e) {
                    $this->error("Error processing message: " . $e->getMessage());
                    // Don't ack - let RabbitMQ requeue
                }
            };

            $channel->basic_consume($queue, '', false, false, false, false, $callback);

            // Add signal handling for graceful shutdown
            declare(ticks=1);
            pcntl_signal(SIGTERM, function() use ($channel, $connection) {
                $this->info('Received SIGTERM, shutting down gracefully...');
                $channel->close();
                $connection->close();
                exit(0);
            });

            while ($channel->is_consuming()) {
                $channel->wait();
            }

        } catch (\Exception $e) {
            $this->error("Fatal error in RabbitMQ consumer: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());
            sleep(5); // Prevent immediate respawn
            throw $e;
        }
    }
}
