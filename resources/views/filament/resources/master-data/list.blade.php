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
                                        @if (($column['type'] ?? '') === 'image')
                                            @php
                                                $slug = \Illuminate\Support\Str::slug($record['name'] ?? '');
                                                $imageUrl = "https://res.cloudinary.com/dblibr1t2/image/upload/v1772780617/{$slug}.png";
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $record['name'] ?? '' }}"
                                                class="h-8 object-contain"
                                                onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'%239ca3af\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z\' /%3E%3C/svg%3E';" />
                                        @else
                                            <span class="text-sm text-gray-950 dark:text-white">
                                                {{ $record[$column['key']] ?? '-' }}
                                            </span>
                                        @endif
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
                    <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
                        {{-- Records Info --}}
                        <div class="flex items-center gap-x-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Showing page <span
                                    class="font-medium text-gray-950 dark:text-white">{{ $page }}</span>
                                of <span class="font-medium text-gray-950 dark:text-white">{{ $totalPages }}</span>
                                &middot; {{ $total }} total records
                            </p>
                        </div>

                        {{-- Pagination Controls --}}
                        <div class="flex flex-col gap-y-3 sm:flex-row sm:items-center sm:gap-x-4">
                            {{-- Page Size Selector --}}
                            <div class="flex items-center gap-x-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Per page:</span>
                                <x-filament::input.wrapper>
                                    <x-filament::input.select wire:model.live="pageSize" class="text-sm">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="all">All</option>
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            </div>

                            {{-- Page Numbers --}}
                            <div class="flex items-center gap-x-1">
                                {{-- Previous Button --}}
                                <x-filament::button wire:click="previousPage" :disabled="$page <= 1" size="sm"
                                    color="gray" icon="heroicon-m-chevron-left" icon-size="sm">
                                </x-filament::button>

                                {{-- Page Numbers --}}
                                @php
                                    $start = max(1, $page - 2);
                                    $end = min($totalPages, $page + 2);
                                    if ($end - $start < 4) {
                                        if ($start == 1) {
                                            $end = min($totalPages, $start + 4);
                                        } else {
                                            $start = max(1, $end - 4);
                                        }
                                    }
                                @endphp

                                @if ($start > 1)
                                    <x-filament::button wire:click="goToPage(1)" size="sm" color="gray">
                                        1
                                    </x-filament::button>
                                    @if ($start > 2)
                                        <span class="px-1 text-sm text-gray-500 dark:text-gray-400">...</span>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    <x-filament::button wire:click="goToPage({{ $i }})" size="sm"
                                        :color="$page == $i ? 'primary' : 'gray'">
                                        {{ $i }}
                                    </x-filament::button>
                                @endfor

                                @if ($end < $totalPages)
                                    @if ($end < $totalPages - 1)
                                        <span class="px-1 text-sm text-gray-500 dark:text-gray-400">...</span>
                                    @endif
                                    <x-filament::button wire:click="goToPage({{ $totalPages }})" size="sm"
                                        color="gray">
                                        {{ $totalPages }}
                                    </x-filament::button>
                                @endif

                                {{-- Next Button --}}
                                <x-filament::button wire:click="nextPage" :disabled="$page >= $totalPages" size="sm"
                                    color="gray" icon="heroicon-m-chevron-right" icon-position="after" icon-size="sm">
                                </x-filament::button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
