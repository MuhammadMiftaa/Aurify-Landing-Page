<?php

namespace App\Filament\Resources\AssetCodeResource\Pages;

use App\Filament\Resources\AssetCodeResource;
use App\Services\MasterDataService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class CreateAssetCode extends Page
{
    protected static string $resource = AssetCodeResource::class;

    protected static string $view = 'filament.resources.master-data.create-asset-code';

    protected static ?string $title = 'Create Asset Code';

    public string $code = '';
    public string $name = '';
    public string $unit = '';

    public function save(): void
    {
        $this->validate([
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
        ]);

        $service = new MasterDataService();
        $service->createAssetCode([
            'code' => strtoupper($this->code),
            'name' => $this->name,
            'unit' => $this->unit,
        ]);

        Notification::make()
            ->title('Asset code creation queued')
            ->body('The asset code will be created once the message is processed.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }

    public function cancel(): void
    {
        $this->redirect(static::$resource::getUrl('index'));
    }
}
