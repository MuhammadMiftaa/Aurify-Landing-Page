<?php

namespace App\Filament\Resources\WalletTypeResource\Pages;

use App\Filament\Resources\WalletTypeResource;
use App\Services\MasterDataService;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

class CreateWalletType extends Page
{
    protected static string $resource = WalletTypeResource::class;

    protected static string $view = 'filament.resources.master-data.create-wallet-type';

    protected static ?string $title = 'Create Wallet Type';

    public string $name = '';
    public string $type = '';
    public string $description = '';

    public function create(): void
    {
        $this->validate([
            'name' => 'required|max:50',
            'type' => 'required|in:bank,e-wallet,physical,others',
        ]);

        $service = new MasterDataService();
        $service->createWalletType([
            'name'        => $this->name,
            'type'        => $this->type,
            'description' => $this->description,
        ]);

        Notification::make()
            ->title('Wallet Type Queued')
            ->body('The wallet type creation has been queued and will be processed shortly.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }
}
