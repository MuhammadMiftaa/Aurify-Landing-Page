<?php

namespace App\Filament\Resources\TransactionCategoryResource\Pages;

use App\Filament\Resources\TransactionCategoryResource;
use App\Services\Grpc\GrpcClient;
use App\Services\MasterDataService;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class CreateTransactionCategory extends Page
{
    protected static string $resource = TransactionCategoryResource::class;

    protected static string $view = 'filament.resources.master-data.create-transaction-category';

    protected static ?string $title = 'Create Transaction Category';

    public string $uuid = '';
    public string $name = '';
    public string $type = '';
    public string $parentId = '';

    public array $parentCategories = [];

    public function mount(): void
    {
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

        // Filter only parent categories (categories without parent_id)
        $this->parentCategories = array_filter(
            $result['categories'] ?? [],
            fn($category) => empty($category['parentId']) && empty($category['parent_id'])
        );
    }

    public function generateUuid(): void
    {
        $this->uuid = Str::uuid()->toString();
    }

    public function create(): void
    {
        $this->validate([
            'uuid' => 'nullable|uuid',
            'name' => 'required|max:50',
            'type' => 'required_without:parentId|in:income,expense,fund_transfer,',
        ]);

        $data = [
            'name' => $this->name,
            'type' => $this->type,
        ];

        if (!empty($this->uuid)) {
            $data['id'] = $this->uuid;
        }

        if ($this->parentId) {
            $data['parent_id'] = $this->parentId;
        }

        $service = new MasterDataService();
        $service->createTransactionCategory($data);

        Notification::make()
            ->title('Category Queued')
            ->body('The category creation has been queued and will be processed shortly.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }
}
