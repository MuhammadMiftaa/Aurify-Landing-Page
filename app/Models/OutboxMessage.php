<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboxMessage extends Model
{
    protected $table = 'outbox_messages';

    protected $fillable = [
        'aggregate_id',
        'event_type',
        'payload',
        'published',
        'published_at',
        'retries',
        'max_retries',
    ];

    protected function casts(): array
    {
        return [
            'payload'      => 'array',
            'published'    => 'boolean',
            'published_at' => 'datetime',
            'retries'      => 'integer',
            'max_retries'  => 'integer',
        ];
    }

    // ── Scopes ──

    public function scopePending($query)
    {
        return $query->where('published', false)
            ->whereColumn('retries', '<', 'max_retries')
            ->orderBy('created_at', 'asc');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    // ── Methods ──

    public function markAsPublished(): void
    {
        $this->update([
            'published'    => true,
            'published_at' => now(),
        ]);
    }

    public function incrementRetries(): void
    {
        $this->increment('retries');
    }
}
