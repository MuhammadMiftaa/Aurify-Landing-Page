<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Activitylog\Models\Activity;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Leads', Lead::count())
                ->description('All time submissions')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
                ->chart(
                    Lead::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->limit(7)
                        ->pluck('count')
                        ->toArray() ?: [0]
                ),

            Stat::make('Leads Today', Lead::whereDate('created_at', today())->count())
                ->description('Submitted today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Leads This Week', Lead::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count())
                ->description('Current week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Activity Logs', Activity::count())
                ->description('All recorded actions')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('gray'),
        ];
    }
}
