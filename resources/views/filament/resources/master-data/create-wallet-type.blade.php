<x-filament-panels::page>
    <form wire:submit="create" class="space-y-6">
        <div
            class="fi-fo-component-ctn rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                {{-- UUID --}}
                <div class="col-span-full">
                    <label for="uuid" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">UUID</span>
                    </label>
                    <div class="mt-1 flex items-center gap-x-2">
                        <x-filament::input.wrapper class="flex-1">
                            <x-filament::input type="text" wire:model="uuid" id="uuid"
                                placeholder="Leave empty to auto-generate" />
                        </x-filament::input.wrapper>
                        <x-filament::button type="button" wire:click="generateUuid" color="gray" size="sm">
                            Generate
                        </x-filament::button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional. Leave empty to let the system
                        generate one.</p>
                    @error('uuid')
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div class="col-span-full sm:col-span-1">
                    <label for="name" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Name <sup class="text-danger-600 dark:text-danger-400">*</sup>
                        </span>
                    </label>
                    <div class="mt-1">
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="name" id="name"
                                placeholder="e.g. BCA, OVO, Cash" required />
                        </x-filament::input.wrapper>
                        @error('name')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Type --}}
                <div class="col-span-full sm:col-span-1">
                    <label for="type" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Type <sup class="text-danger-600 dark:text-danger-400">*</sup>
                        </span>
                    </label>
                    <div class="mt-1">
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model.live="type" id="type" required>
                                <option value="">Select type...</option>
                                @foreach (\App\Filament\Resources\WalletTypeResource::types() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                        @error('type')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Nature --}}
                <div class="col-span-full sm:col-span-1">
                    <label for="nature" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Nature <sup class="text-danger-600 dark:text-danger-400">*</sup>
                        </span>
                    </label>
                    <div class="mt-1">
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model="nature" id="nature" required>
                                @foreach (\App\Filament\Resources\WalletTypeResource::natures() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            A liability holds a credit limit rather than money. Its balance is the credit still
                            available, and it is excluded from the user's net worth.
                        </p>
                        @error('nature')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Logo --}}
                <div class="col-span-full sm:col-span-1">
                    <label for="logo" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Logo</span>
                    </label>
                    <div class="mt-1 flex items-center gap-x-3">
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview"
                                class="h-12 w-12 rounded object-contain ring-1 ring-gray-950/10 dark:ring-white/10" />
                        @endif
                        <input type="file" wire:model="logo" id="logo" accept="image/png,image/jpeg,image/webp"
                            class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-600 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-white hover:file:bg-primary-500 dark:text-gray-400" />
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Optional. PNG/JPG/WebP, max 512&nbsp;KB. Leave empty to use the default logo.
                    </p>
                    <div wire:loading wire:target="logo" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Uploading&hellip;
                    </div>
                    @error('logo')
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-span-full">
                    <label for="description" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Description</span>
                    </label>
                    <div class="mt-1">
                        <x-filament::input.wrapper>
                            <textarea wire:model="description" id="description" rows="3" placeholder="Optional description..."
                                class="fi-input block w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)]"></textarea>
                        </x-filament::input.wrapper>
                        @error('description')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="fi-form-actions flex flex-wrap items-center justify-start gap-4">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                Create Wallet Type
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
