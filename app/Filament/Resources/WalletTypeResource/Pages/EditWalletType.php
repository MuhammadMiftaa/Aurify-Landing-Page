<?php

namespace App\Filament\Resources\WalletTypeResource\Pages;

use App\Filament\Resources\WalletTypeResource;
use App\Services\Grpc\GrpcClient;
use App\Services\MasterDataService;
use App\Services\WalletTypeLogoService;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class EditWalletType extends Page
{
    use WithFileUploads;

    protected static string $resource = WalletTypeResource::class;

    protected static string $view = 'filament.resources.master-data.edit-wallet-type';

    protected static ?string $title = 'Edit Wallet Type';

    public string $recordId = '';
    public string $name = '';
    public string $type = '';
    public string $nature = WalletTypeResource::NATURE_ASSET;
    public string $description = '';
    public string $iconUrl = '';

    /** @var TemporaryUploadedFile|null */
    public $logo = null;

    public function mount(string $record): void
    {
        $this->recordId = $record;

        $grpc = GrpcClient::make();
        $detail = $grpc->getWalletTypeDetail($record);

        if ($detail) {
            $this->name = $detail['name'] ?? '';
            $this->type = $detail['type'] ?? '';
            // Hydrating these matters: the update event carries every field, so
            // anything not loaded here would be published back as empty and
            // wipe the stored value.
            $this->nature = $detail['nature'] ?: WalletTypeResource::NATURE_ASSET;
            $this->description = $detail['description'] ?? '';
            $this->iconUrl = $detail['icon_url'] ?? '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|max:50',
            'type' => 'required|in:' . implode(',', array_keys(WalletTypeResource::types())),
            'nature' => 'required|in:' . implode(',', array_keys(WalletTypeResource::natures())),
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:512',
        ]);

        $logoService = new WalletTypeLogoService();
        $iconUrl = $this->iconUrl;

        if ($this->logo) {
            $iconUrl = $logoService->upload($this->logo);
            $logoService->deleteByUrl($this->iconUrl);
        }

        $service = new MasterDataService();
        $service->updateWalletType($this->recordId, [
            'id' => $this->recordId,
            'name' => $this->name,
            'type' => $this->type,
            'nature' => $this->nature,
            'description' => $this->description,
            'icon_url' => $iconUrl,
        ]);

        Notification::make()
            ->title('Wallet Type Update Queued')
            ->body('The wallet type update has been queued and will be processed shortly.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }

    /** Removes the logo without touching the rest of the record. */
    public function removeLogo(): void
    {
        $this->logo = null;
        $this->iconUrl = '';

        Notification::make()
            ->title('Logo cleared')
            ->body('Save to apply. The app will fall back to the default logo.')
            ->success()
            ->send();
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
