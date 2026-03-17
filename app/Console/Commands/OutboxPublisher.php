<?php

namespace App\Console\Commands;

use App\Models\OutboxMessage;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OutboxPublisher extends Command
{
    protected $signature = 'outbox:publish {--interval=60 : Polling interval in seconds}';

    protected $description = 'Poll outbox_messages and publish pending messages to RabbitMQ';

    public function handle(): int
    {
        $interval = (int) $this->option('interval');
        $rabbitMQ = new RabbitMQService();

        $this->info("Outbox publisher started (interval: {$interval}s)");
        Log::info('outbox_publisher_started', [
            'service'  => 'outbox',
            'interval' => $interval,
        ]);

        while (true) {
            try {
                $this->publishPendingMessages($rabbitMQ);
            } catch (\Exception $e) {
                Log::error('outbox_publish_pending_failed', [
                    'service' => 'outbox',
                    'error'   => $e->getMessage(),
                ]);
                $this->error("Error: {$e->getMessage()}");

                // Reconnect on next iteration
                $rabbitMQ->disconnect();
            }

            sleep($interval);
        }

        return self::SUCCESS;
    }

    private function publishPendingMessages(RabbitMQService $rabbitMQ): void
    {
        $messages = OutboxMessage::pending()->limit(100)->get();

        if ($messages->isEmpty()) {
            return;
        }

        foreach ($messages as $message) {
            try {
                $rabbitMQ->publish($message->event_type, $message->payload);

                $message->markAsPublished();

                Log::info('outbox_message_published', [
                    'service'    => 'outbox',
                    'message_id' => $message->id,
                    'event_type' => $message->event_type,
                ]);
            } catch (\Exception $e) {
                Log::error('outbox_message_publish_failed', [
                    'service'    => 'outbox',
                    'message_id' => $message->id,
                    'event_type' => $message->event_type,
                    'error'      => $e->getMessage(),
                ]);

                if ($message->retries >= $message->max_retries - 1) {
                    Log::error('outbox_message_max_retries_exceeded', [
                        'service'    => 'outbox',
                        'message_id' => $message->id,
                        'event_type' => $message->event_type,
                        'retries'    => $message->retries,
                    ]);
                }

                $message->incrementRetries();
            }
        }
    }
}
