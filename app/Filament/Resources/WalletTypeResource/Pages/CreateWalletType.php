<?php

namespace App\Filament\Resources\WalletTypeResource\Pages;

use App\Filament\Resources\WalletTypeResource;
use App\Services\MasterDataService;
use App\Services\WalletTypeLogoService;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class CreateWalletType extends Page
{
    use WithFileUploads;

    protected static string $resource = WalletTypeResource::class;

    protected static string $view = 'filament.resources.master-data.create-wallet-type';

    protected static ?string $title = 'Create Wallet Type';

    public string $uuid = '';
    public string $name = '';
    public string $type = '';
    public string $nature = 'asset';
    public string $description = '';

    /** @var TemporaryUploadedFile|null */
    public $logo = null;

    public function generateUuid(): void
    {
        $this->uuid = Str::uuid()->toString();
    }

    /**
     * Credit lines default to `liability` as soon as the type is chosen, since
     * picking `credit_card` and leaving it an asset is always a mistake — it
     * would put the card's unused limit into the user's net worth.
     */
    public function updatedType(string $value): void
    {
        $this->nature = $value === WalletTypeResource::TYPE_CREDIT_CARD
            ? WalletTypeResource::NATURE_LIABILITY
            : WalletTypeResource::NATURE_ASSET;
    }

    public function create(): void
    {
        $this->validate([
            'uuid' => 'nullable|uuid',
            'name' => 'required|max:50',
            'type' => 'required|in:' . implode(',', array_keys(WalletTypeResource::types())),
            'nature' => 'required|in:' . implode(',', array_keys(WalletTypeResource::natures())),
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:512',
        ]);

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'nature' => $this->nature,
            'description' => $this->description,
        ];

        if ($this->logo) {
            $data['icon_url'] = (new WalletTypeLogoService())->upload($this->logo);
        }

        if (! empty($this->uuid)) {
            $data['id'] = $this->uuid;
        }

        $service = new MasterDataService();
        $service->createWalletType($data);

        Notification::make()
            ->title('Wallet Type Queued')
            ->body('The wallet type creation has been queued and will be processed shortly.')
            ->success()
            ->send();

        $this->redirect(static::$resource::getUrl('index'));
    }
}
