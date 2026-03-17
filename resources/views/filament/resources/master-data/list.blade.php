<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Search --}}
        <div class="fi-ta-header-toolbar flex items-center gap-x-4">
            <div class="flex-1">
                <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                    <x-filament::input type="text" wire:model.live.debounce.500ms="search" placeholder="Search..." />
                </x-filament::input.wrapper>
            </div>
        </div>

        {{-- Table --}}
        <div class="fi-ta rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-ta-content overflow-x-auto">
                <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            @foreach ($columns as $column)
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    @if ($column['sortable'])
                                        <button wire:click="sort('{{ $column['key'] }}')"
                                            class="flex items-center gap-x-1 text-sm font-semibold text-gray-950 dark:text-white">
                                            {{ $column['label'] }}
                                            @if ($sortBy === $column['key'])
                                                @if ($sortOrder === 'asc')
                                                    <x-heroicon-m-chevron-up class="h-4 w-4" />
                                                @else
                                                    <x-heroicon-m-chevron-down class="h-4 w-4" />
                                                @endif
                                            @else
                                                <x-heroicon-m-chevron-up-down class="h-4 w-4 text-gray-400" />
                                            @endif
                                        </button>
                                    @else
                                        <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                            {{ $column['label'] }}
                                        </span>
                                    @endif
                                </th>
                            @endforeach
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:last-of-type:pe-6">
                                <span class="text-sm font-semibold text-gray-950 dark:text-white">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                        @forelse($records as $record)
                            <tr class="fi-ta-row transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5">
                                @foreach ($columns as $column)
                                    <td class="fi-ta-cell px-3 py-4 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                        <span class="text-sm text-gray-950 dark:text-white">
                                            {{ $record[$column['key']] ?? '-' }}
                                        </span>
                                    </td>
                                @endforeach
                                <td class="fi-ta-cell px-3 py-4 sm:last-of-type:pe-6">
                                    @php
                                        $editId = $record['id'] ?? ($record['code'] ?? '');
                                    @endphp
                                    <a href="{{ $this->getEditUrl($editId) }}"
                                        class="fi-link fi-link-size-sm inline-flex items-center gap-1 text-sm font-semibold text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                                        <x-heroicon-m-pencil-square class="h-4 w-4" />
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" class="px-3 py-6 text-center">
                                    <div class="flex flex-col items-center gap-y-2">
                                        <x-heroicon-o-inbox class="h-8 w-8 text-gray-400 dark:text-gray-500" />
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($totalPages > 0)
                <div class="fi-ta-footer border-t border-gray-200 px-4 py-3 dark:border-white/10">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Showing page <span
                                class="font-medium text-gray-950 dark:text-white">{{ $page }}</span>
                            of <span class="font-medium text-gray-950 dark:text-white">{{ $totalPages }}</span>
                            &middot; {{ $total }} total records
                        </p>
                        <div class="flex items-center gap-x-2">
                            <x-filament::button wire:click="previousPage" :disabled="$page <= 1" size="sm"
                                color="gray" icon="heroicon-m-chevron-left">
                                Previous
                            </x-filament::button>
                            <x-filament::button wire:click="nextPage" :disabled="$page >= $totalPages" size="sm" color="gray"
                                icon="heroicon-m-chevron-right" icon-position="after">
                                Next
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
