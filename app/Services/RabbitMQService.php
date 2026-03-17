<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RabbitMQService
{
    private ?AMQPStreamConnection $connection = null;
    private ?AMQPChannel $channel = null;

    private string $host;
    private int $port;
    private string $user;
    private string $password;
    private string $virtualHost;
    private string $exchangeName;
    private string $exchangeType;

    public function __construct()
    {
        $this->host         = config('rabbitmq.host');
        $this->port         = (int) config('rabbitmq.port');
        $this->user         = config('rabbitmq.user');
        $this->password     = config('rabbitmq.password');
        $this->virtualHost  = config('rabbitmq.virtual_host');
        $this->exchangeName = config('rabbitmq.exchange.name');
        $this->exchangeType = config('rabbitmq.exchange.type');
    }

    public function connect(): void
    {
        if ($this->connection && $this->connection->isConnected()) {
            return;
        }

        try {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->password,
                $this->virtualHost,
            );
            $this->channel = $this->connection->channel();

            // Declare exchange
            $this->channel->exchange_declare(
                $this->exchangeName,
                $this->exchangeType,
                false,  // passive
                true,   // durable
                false,  // auto_delete
            );

            Log::info('rabbitmq_connected', [
                'service' => 'rabbitmq',
                'host'    => $this->host,
                'port'    => $this->port,
            ]);
        } catch (\Exception $e) {
            Log::error('rabbitmq_connect_failed', [
                'service' => 'rabbitmq',
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function publish(string $routingKey, array $payload): void
    {
        $this->connect();

        $message = new AMQPMessage(
            json_encode($payload),
            [
                'content_type'  => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'timestamp'     => time(),
            ],
        );

        $this->channel->basic_publish(
            $message,
            $this->exchangeName,
            $routingKey,
        );

        Log::info('rabbitmq_message_published', [
            'service'     => 'rabbitmq',
            'routing_key' => $routingKey,
        ]);
    }

    public function disconnect(): void
    {
        try {
            if ($this->channel) {
                $this->channel->close();
                $this->channel = null;
            }
            if ($this->connection) {
                $this->connection->close();
                $this->connection = null;
            }
            Log::info('rabbitmq_disconnected', ['service' => 'rabbitmq']);
        } catch (\Exception $e) {
            Log::warning('rabbitmq_disconnect_failed', [
                'service' => 'rabbitmq',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
