<?php

namespace App\Filament\Resources\TransactionCategoryResource\Pages;

use App\Filament\Resources\TransactionCategoryResource;
use App\Services\Grpc\GrpcClient;
use App\Services\MasterDataService;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

class EditTransactionCategory extends Page
{
    protected static string $resource = TransactionCategoryResource::class;

    protected static string $view = 'filament.resources.master-data.edit-transaction-category';

    protected static ?string $title = 'Edit Transaction Category';

    public string $recordId = '';
    public string $name = '';
    public string $type = '';
    public string $parentId = '';

    public array $parentCategories = [];

    public function mount(string $record): void
    {
        $this->recordId = $record;

        $grpc = GrpcClient::make();;
        $detail = $grpc->getCategoryDetail($record);

        if ($detail) {
            $this->name     = $detail['name'] ?? '';
            $this->type     = $detail['type'] ?? '';
            $this->parentId = $detail['parentId'] ?? $detail['parent_id'] ?? '';
        }

        $this->loadParentCategories();
    }

    public function loadParentCategories(): void
    {
        $grpc = GrpcClient::make();;
        $result = $grpc->listCategories(
            page: 1,
            pageSize: 1000,
            sortBy: 'name',
            sortOrder: 'asc',
            search: '',
        );

        // Filter only parent categories (categories without parent_id) and exclude current record
        $this->parentCategories = array_filter(
            $result['categories'] ?? [],
            fn($category) => (empty($category['parentId']) && empty($category['parent_id']))
                && ($category['id'] ?? '') !== $this->recordId
        );
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|max:50',
        ]);

        $data = [
            'id'   => $this->recordId,
            'name' => $this->name,
            'type' => $this->type,
        ];

        if ($this->parentId) {
            $data['parent_id'] = $this->parentId;
        }

        $service = new MasterDataService();
        $service->updateTransactionCategory($this->recordId, $data);

        Notification::make()
            ->title('Category Update Queued')
            ->body('The category update has been queued and will be processed shortly.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }

    public function delete(): void
    {
        $service = new MasterDataService();
        $service->deleteTransactionCategory($this->recordId);

        Notification::make()
            ->title('Category Deletion Queued')
            ->body('The category deletion has been queued and will be processed shortly.')
            ->warning()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }
}
