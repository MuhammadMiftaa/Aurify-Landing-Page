<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <div
            class="fi-fo-component-ctn rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                {{-- Name --}}
                <div class="col-span-full sm:col-span-1">
                    <label for="name" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Name <sup class="text-danger-600 dark:text-danger-400">*</sup>
                        </span>
                    </label>
                    <div class="mt-1">
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="name" id="name" required />
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
                            <x-filament::input.select wire:model="type" id="type" required>
                                <option value="">Select type...</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                                <option value="fund_transfer">Fund Transfer</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                        @error('type')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Parent ID --}}
                <div class="col-span-full sm:col-span-1">
                    <label for="parentId" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Parent ID</span>
                    </label>
                    <div class="mt-1">
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="parentId" id="parentId"
                                placeholder="Optional parent category UUID" />
                        </x-filament::input.wrapper>
                        @error('parentId')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="fi-form-actions flex flex-wrap items-center justify-start gap-4">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                Save Changes
            </x-filament::button>
            <x-filament::button type="button" color="danger" wire:click="delete"
                wire:confirm="Are you sure you want to delete this category?">
                Delete
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
