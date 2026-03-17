<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Outbox Publisher ──
// Polls outbox_messages every minute and publishes to RabbitMQ (admin exchange).
// Run continuously in production with: php artisan schedule:work
Schedule::command('outbox:publish')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
