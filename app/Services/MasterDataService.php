<?php

namespace App\Services;

use App\Models\OutboxMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MasterDataService
{
    /**
     * Create or update a master data record via the outbox pattern.
     *
     * @param string $routingKey  e.g. 'master.wallet_types'
     * @param string $action      'create' | 'update' | 'delete'
     * @param array  $data        The payload data
     * @param string|null $aggregateId  UUID for updates/deletes; null for creates
     */
    public function dispatch(string $routingKey, string $action, array $data, ?string $aggregateId = null): OutboxMessage
    {
        $aggregateId = $aggregateId ?? (string) Str::uuid();

        $payload = [
            'action'       => $action,
            'aggregate_id' => $aggregateId,
            'data'         => $data,
            'timestamp'    => now()->toISOString(),
        ];

        $outbox = DB::transaction(function () use ($routingKey, $payload, $aggregateId) {
            return OutboxMessage::create([
                'aggregate_id' => $aggregateId,
                'event_type'   => $routingKey,
                'payload'      => $payload,
                'published'    => false,
                'retries'      => 0,
                'max_retries'  => 3,
            ]);
        });

        Log::info('outbox_message_created', [
            'service'      => 'master_data',
            'message_id'   => $outbox->id,
            'event_type'   => $routingKey,
            'action'       => $action,
            'aggregate_id' => $aggregateId,
        ]);

        return $outbox;
    }

    // ── Wallet Types ──

    public function createWalletType(array $data): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.wallet_types'),
            'create',
            $data,
        );
    }

    public function updateWalletType(string $id, array $data): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.wallet_types'),
            'update',
            $data,
            $id,
        );
    }

    public function deleteWalletType(string $id): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.wallet_types'),
            'delete',
            ['id' => $id],
            $id,
        );
    }

    // ── Transaction Categories ──

    public function createTransactionCategory(array $data): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.transaction_categories'),
            'create',
            $data,
        );
    }

    public function updateTransactionCategory(string $id, array $data): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.transaction_categories'),
            'update',
            $data,
            $id,
        );
    }

    public function deleteTransactionCategory(string $id): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.transaction_categories'),
            'delete',
            ['id' => $id],
            $id,
        );
    }

    // ── Asset Codes ──

    public function createAssetCode(array $data): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.asset_codes'),
            'create',
            $data,
        );
    }

    public function updateAssetCode(string $code, array $data): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.asset_codes'),
            'update',
            $data,
            $code,
        );
    }

    public function deleteAssetCode(string $code): OutboxMessage
    {
        return $this->dispatch(
            config('rabbitmq.routing_keys.asset_codes'),
            'delete',
            ['code' => $code],
            $code,
        );
    }
}
