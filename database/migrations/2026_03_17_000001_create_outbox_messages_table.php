<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outbox_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('aggregate_id')->index();
            $table->string('event_type', 255)->index();
            $table->jsonb('payload');
            $table->boolean('published')->default(false)->index();
            $table->timestamp('published_at')->nullable();
            $table->integer('retries')->default(0);
            $table->integer('max_retries')->default(3);
            $table->timestamps();

            // Composite indexes for efficient polling
            $table->index(['published', 'retries', 'created_at'], 'idx_outbox_pending');
        });

        DB::statement("COMMENT ON TABLE outbox_messages IS 'Outbox pattern table for reliable event publishing in CQRS'");
        DB::statement("COMMENT ON COLUMN outbox_messages.aggregate_id IS 'ID of the aggregate (e.g., wallet_type_id)'");
        DB::statement("COMMENT ON COLUMN outbox_messages.event_type IS 'Type of event (e.g., master.wallet_types)'");
        DB::statement("COMMENT ON COLUMN outbox_messages.payload IS 'Event payload in JSON format'");
        DB::statement("COMMENT ON COLUMN outbox_messages.published IS 'Whether the message has been published to message broker'");
        DB::statement("COMMENT ON COLUMN outbox_messages.retries IS 'Number of retry attempts'");
    }

    public function down(): void
    {
        Schema::dropIfExists('outbox_messages');
    }
};
