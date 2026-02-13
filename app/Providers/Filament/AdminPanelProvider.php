<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Aurify')
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => [
                    50  => '#FFF9E6',
                    100 => '#F5DEB3',
                    200 => '#F0D08C',
                    300 => '#E8C36A',
                    400 => '#DAA520',
                    500 => '#FFD700',
                    600 => '#C5961E',
                    700 => '#8B6914',
                    800 => '#8B4513',
                    900 => '#5C2D0A',
                    950 => '#3D1E07',
                ],
                'gray' => [
                    50  => '#F9FAFB',
                    100 => '#F3F4F6',
                    200 => '#E5E7EB',
                    300 => '#D1D5DB',
                    400 => '#9CA3AF',
                    500 => '#6B7280',
                    600 => '#4B5563',
                    700 => '#374151',
                    800 => '#1A1A1A',
                    900 => '#111111',
                    950 => '#0A0A0A',
                ],
            ])
            ->font('Manrope')
            ->darkMode(true, isForced: true)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
