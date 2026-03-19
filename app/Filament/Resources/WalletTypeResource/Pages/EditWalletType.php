<?php

namespace App\Filament\Resources\WalletTypeResource\Pages;

use App\Filament\Resources\WalletTypeResource;
use App\Services\Grpc\GrpcClient;
use App\Services\MasterDataService;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

class EditWalletType extends Page
{
    protected static string $resource = WalletTypeResource::class;

    protected static string $view = 'filament.resources.master-data.edit-wallet-type';

    protected static ?string $title = 'Edit Wallet Type';

    public string $recordId = '';
    public string $name = '';
    public string $type = '';
    public string $description = '';

    public function mount(string $record): void
    {
        $this->recordId = $record;

        $grpc = GrpcClient::make();;
        $detail = $grpc->getWalletTypeDetail($record);

        if ($detail) {
            $this->name        = $detail['name'] ?? '';
            $this->type        = $detail['type'] ?? '';
            $this->description = $detail['description'] ?? '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|max:50',
            'type' => 'required|in:bank,e-wallet,physical,others',
        ]);

        $service = new MasterDataService();
        $service->updateWalletType($this->recordId, [
            'id'          => $this->recordId,
            'name'        => $this->name,
            'type'        => $this->type,
            'description' => $this->description,
        ]);

        Notification::make()
            ->title('Wallet Type Update Queued')
            ->body('The wallet type update has been queued and will be processed shortly.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }

    public function delete(): void
    {
        $service = new MasterDataService();
        $service->deleteWalletType($this->recordId);

        Notification::make()
            ->title('Wallet Type Deletion Queued')
            ->body('The wallet type deletion has been queued and will be processed shortly.')
            ->warning()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }
}
