<?php

namespace App\Filament\Resources\AssetCodeResource\Pages;

use App\Filament\Resources\AssetCodeResource;
use App\Services\Grpc\GrpcClient;
use App\Services\MasterDataService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class EditAssetCode extends Page
{
    protected static string $resource = AssetCodeResource::class;

    protected static string $view = 'filament.resources.master-data.edit-asset-code';

    protected static ?string $title = 'Edit Asset Code';

    public string $recordId = '';
    public string $code = '';
    public string $name = '';
    public string $unit = '';

    public function mount(string $record): void
    {
        $this->recordId = $record;

        $grpc = new GrpcClient();
        $detail = $grpc->getAssetCodeDetail($record);

        if (empty($detail)) {
            Notification::make()
                ->title('Asset code not found')
                ->danger()
                ->send();
            $this->redirect(static::$resource::getUrl('index'));
            return;
        }

        $this->code = $detail['code'] ?? '';
        $this->name = $detail['name'] ?? '';
        $this->unit = $detail['unit'] ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
        ]);

        $service = new MasterDataService();
        $service->updateAssetCode($this->code, [
            'code' => $this->code,
            'name' => $this->name,
            'unit' => $this->unit,
        ]);

        Notification::make()
            ->title('Asset code update queued')
            ->body('The changes will be applied once the message is processed.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }

    public function delete(): void
    {
        $service = new MasterDataService();
        $service->deleteAssetCode($this->code);

        Notification::make()
            ->title('Asset code deletion queued')
            ->body('The asset code will be deleted once the message is processed.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }

    public function cancel(): void
    {
        $this->redirect(static::$resource::getUrl('index'));
    }
}
