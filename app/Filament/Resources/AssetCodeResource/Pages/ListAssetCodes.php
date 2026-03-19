<?php

namespace App\Filament\Resources\AssetCodeResource\Pages;

use App\Filament\Resources\AssetCodeResource;
use App\Services\Grpc\GrpcClient;
use Filament\Resources\Pages\Page;
use Filament\Actions;

class ListAssetCodes extends Page
{
    protected static string $resource = AssetCodeResource::class;

    protected static string $view = 'filament.resources.master-data.list';

    protected static ?string $title = 'Asset Codes';

    public int $page = 1;
    public string|int $pageSize = 10;
    public string $search = '';
    public string $sortBy = 'code';
    public string $sortOrder = 'asc';

    public array $records = [];
    public int $total = 0;
    public int $totalPages = 0;
    public string $masterType = 'asset_codes';

    public array $columns = [
        ['key' => 'code',      'label' => 'Code',      'sortable' => true],
        ['key' => 'name',      'label' => 'Name',      'sortable' => true],
        ['key' => 'unit',      'label' => 'Unit',      'sortable' => false],
        ['key' => 'toUSD',     'label' => 'To USD',    'sortable' => true],
        ['key' => 'toEUR',     'label' => 'To EUR',    'sortable' => true],
        ['key' => 'toIDR',     'label' => 'To IDR',    'sortable' => true],
        ['key' => 'createdAt', 'label' => 'Created',   'sortable' => true],
    ];

    public function mount(): void
    {
        $this->loadRecords();
    }

    public function loadRecords(): void
    {
        $grpc = GrpcClient::make();;

        // Handle "all" pageSize
        $pageSize = $this->pageSize === 'all' ? 9999 : (int) $this->pageSize;

        $result = $grpc->listAssetCodes(
            page: $this->page,
            pageSize: $pageSize,
            sortBy: $this->sortBy,
            sortOrder: $this->sortOrder,
            search: $this->search,
        );

        $this->records    = $result['assetCodes'] ?? $result['asset_codes'] ?? [];
        $this->total      = $result['total'] ?? 0;
        $this->totalPages = $result['totalPages'] ?? $result['total_pages'] ?? 0;

        // If "all", set totalPages to 1
        if ($this->pageSize === 'all') {
            $this->totalPages = 1;
        }
    }

    public function updatedSearch(): void
    {
        $this->resetAndReload();
    }

    public function updatedPageSize(): void
    {
        $this->resetAndReload();
    }

    public function updatedPage(): void
    {
        if ($this->page < 1) {
            $this->page = 1;
        } elseif ($this->page > $this->totalPages && $this->totalPages > 0) {
            $this->page = $this->totalPages;
        }
        $this->loadRecords();
    }

    protected function resetAndReload(): void
    {
        $this->page = 1;
        $this->loadRecords();
    }

    public function goToPage(int $page): void
    {
        $this->page = $page;
        $this->loadRecords();
    }

    public function previousPage(): void
    {
        if ($this->page > 1) {
            $this->page--;
            $this->loadRecords();
        }
    }

    public function nextPage(): void
    {
        if ($this->page < $this->totalPages) {
            $this->page++;
            $this->loadRecords();
        }
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortOrder = 'asc';
        }
        $this->page = 1;
        $this->loadRecords();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('New Asset Code')
                ->url(static::$resource::getUrl('create'))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getEditUrl(string $id): string
    {
        return static::$resource::getUrl('edit', ['record' => $id]);
    }
}
