<?php

namespace App\Providers;

use App\Listeners\AuthActivityLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(Login::class, [AuthActivityLogger::class, 'handleLogin']);
        Event::listen(Logout::class, [AuthActivityLogger::class, 'handleLogout']);
    }
}
