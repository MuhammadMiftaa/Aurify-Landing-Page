<?php

namespace App\Filament\Resources\TransactionCategoryResource\Pages;

use App\Filament\Resources\TransactionCategoryResource;
use App\Services\Grpc\GrpcClient;
use Filament\Resources\Pages\Page;
use Filament\Actions;

class ListTransactionCategories extends Page
{
    protected static string $resource = TransactionCategoryResource::class;

    protected static string $view = 'filament.resources.master-data.list';

    protected static ?string $title = 'Transaction Categories';

    public int $page = 1;
    public string|int $pageSize = 10;
    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortOrder = 'desc';

    public array $records = [];
    public int $total = 0;
    public int $totalPages = 0;
    public string $masterType = 'transaction_categories';

    public array $columns = [
        ['key' => 'id',         'label' => 'ID',          'sortable' => true],
        ['key' => 'name',       'label' => 'Name',        'sortable' => true],
        ['key' => 'type',       'label' => 'Type',        'sortable' => true],
        ['key' => 'parentName', 'label' => 'Parent',      'sortable' => true],
        ['key' => 'createdAt',  'label' => 'Created',     'sortable' => true],
    ];

    public function mount(): void
    {
        $this->loadRecords();
    }

    public function loadRecords(): void
    {
        $grpc = new GrpcClient();

        // Handle "all" pageSize
        $pageSize = $this->pageSize === 'all' ? 9999 : (int) $this->pageSize;

        $result = $grpc->listCategories(
            page: $this->page,
            pageSize: $pageSize,
            sortBy: $this->sortBy,
            sortOrder: $this->sortOrder,
            search: $this->search,
        );

        $this->records    = $result['categories'] ?? [];
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
                ->label('New Category')
                ->url(static::$resource::getUrl('create'))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getEditUrl(string $id): string
    {
        return static::$resource::getUrl('edit', ['record' => $id]);
    }
}
