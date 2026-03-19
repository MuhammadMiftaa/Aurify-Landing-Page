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
                                placeholder="e.g. Groceries, Salary" required />
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

                {{-- Parent Category --}}
                <div class="col-span-full sm:col-span-1">
                    <label for="parentId" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Parent Category</span>
                    </label>
                    <div class="mt-1">
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model="parentId" id="parentId">
                                <option value="">No parent (root category)</option>
                                @foreach ($parentCategories as $category)
                                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                @endforeach
                            </x-filament::input.select>
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
                Create Category
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
