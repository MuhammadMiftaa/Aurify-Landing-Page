<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;

class LeadsChart extends ChartWidget
{
    protected static ?string $heading = 'Leads Over Time';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Lead::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Leads',
                    'data' => array_values($data) ?: [0],
                    'borderColor' => '#DAA520',
                    'backgroundColor' => 'rgba(218, 165, 32, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => array_map(fn($d) => date('d M', strtotime($d)), array_keys($data)) ?: ['Today'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
