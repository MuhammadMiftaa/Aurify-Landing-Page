<?php

namespace App\Filament\Resources\TransactionCategoryResource\Pages;

use App\Filament\Resources\TransactionCategoryResource;
use App\Services\MasterDataService;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

class CreateTransactionCategory extends Page
{
    protected static string $resource = TransactionCategoryResource::class;

    protected static string $view = 'filament.resources.master-data.create-transaction-category';

    protected static ?string $title = 'Create Transaction Category';

    public string $name = '';
    public string $type = '';
    public string $parentId = '';

    public function create(): void
    {
        $this->validate([
            'name' => 'required|max:50',
            'type' => 'required_without:parentId|in:income,expense,fund_transfer,',
        ]);

        $data = [
            'name' => $this->name,
            'type' => $this->type,
        ];

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
