<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class AuthActivityLogger
{
    public function handleLogin(Login $event): void
    {
        /** @var \Illuminate\Database\Eloquent\Model $user */
        $user = $event->user;
        activity('auth')
            ->causedBy($user)
            ->withProperties([
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User logged in');
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            /** @var \Illuminate\Database\Eloquent\Model $user */
            $user = $event->user;
            activity('auth')
                ->causedBy($user)
                ->withProperties([
                    'ip' => request()->ip(),
                ])
                ->log('User logged out');
        }
    }
}
