<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Jobs\ProcessSpending;
class ConsumeSpendings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'app:consume-spendings';
    protected $signature = 'rabbitmq:consume-spendings';

    /**
     * The console command description.
     *
     * @var string
     */
//    protected $description = 'Command description';
    protected $description = 'Consume spending messages from RabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(
            config('queue.connections.rabbitmq.hosts.0.host'),
            config('queue.connections.rabbitmq.hosts.0.port'),
            config('queue.connections.rabbitmq.hosts.0.user'),
            config('queue.connections.rabbitmq.hosts.0.password'),
            config('queue.connections.rabbitmq.hosts.0.vhost')
        );

        $channel = $connection->channel();

        $queue = 'default';

        $channel->queue_declare($queue, false, true, false, false);

        $this->info("Waiting for messages in queue: {$queue}. To exit press CTRL+C");

        $callback = function ($msg) {
            try {
                $data = json_decode($msg->body, true);

                $this->info("Received message: " . $msg->body);

                // Dispatch the job to process the spending
                ProcessSpending::dispatch(
                    $data['counterId'],
                    $data['energyWh'],
                    $data['created_at']
                );

                $this->info("Job dispatched for counter: " . $data['counterId']);

                // Acknowledge the message
                $msg->ack();

            } catch (\Exception $e) {
                $this->error("Error processing message: " . $e->getMessage());
                // You might want to handle the error differently
            }
        };

        $channel->basic_consume($queue, '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
