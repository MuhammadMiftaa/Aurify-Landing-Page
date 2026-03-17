<?php

namespace App\Console\Commands;

use App\Models\OutboxMessage;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OutboxPublisher extends Command
{
    protected $signature = 'outbox:publish';

    protected $description = 'Publish pending outbox_messages to RabbitMQ (run via scheduler every minute)';

    public function handle(): int
    {
        $rabbitMQ = new RabbitMQService();

        Log::info('outbox_publisher_started', ['service' => 'outbox']);

        try {
            $this->publishPendingMessages($rabbitMQ);
        } catch (\Exception $e) {
            Log::error('outbox_publish_pending_failed', [
                'service' => 'outbox',
                'error'   => $e->getMessage(),
            ]);
            $this->error("Error: {$e->getMessage()}");
            return self::FAILURE;
        } finally {
            $rabbitMQ->disconnect();
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
