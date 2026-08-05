@props([
    'categories',
    'selectedCategory',
    'selectedSort',
    'searchPlaceholder' => 'Cari produk..',
    'showAllCategories' => false,
])

<div class="mb-5 space-y-4">
  <div
    class="flex flex-wrap gap-4 xl:flex-row xl:items-center xl:justify-between">

    <div
      @if ($showAllCategories) x-data="{ showAll: false }" @endif
      class="flex w-full items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide whitespace-nowrap md:overflow-x-hidden xl:w-auto"
      @if ($showAllCategories) :class="showAll ? 'md:!overflow-x-auto' : ''" @endif>
      @foreach ($categories as $category)
        <button type="button"
          wire:click="selectCategory('{{ $category->slug }}')"
          wire:loading.attr="disabled"
          wire:target="selectCategory('{{ $category->slug }}')"
          class="filter-btn flex-shrink-0 inline-flex items-center gap-1.5 whitespace-nowrap px-4 py-2 rounded-full text-xs font-medium border border-primary/20 hover:border-primary transition-colors duration-200 cursor-pointer {{ $selectedCategory === $category->slug ? 'active' : '' }} {{ $showAllCategories && $loop->index >= 6 ? 'md:hidden' : '' }}"
          @if ($showAllCategories && $loop->index >= 6) :class="{ 'md:!flex': showAll }" @endif>

          <x-icons.spinner wire:loading
            wire:target="selectCategory('{{ $category->slug }}')"
            class="h-3 w-3 text-current" />

          {{ $category->name }}
        </button>
      @endforeach

      @if ($showAllCategories && count($categories) > 6)
        <button type="button" @click="showAll = !showAll"
          class="hidden md:inline-flex flex-shrink-0 items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold border border-primary/20 hover:border-primary transition-colors duration-200 bg-white text-primary cursor-pointer">
          <span x-text="showAll ? 'Lebih sedikit' : 'Selengkapnya'"></span>
          <svg
            class="w-3.5 h-3.5 transition-transform duration-200"
            :class="showAll ? 'rotate-180' : ''"
            fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      @endif
    </div>

    <div
      class="flex w-full gap-2 items-center xl:ml-auto xl:max-w-[560px] xl:justify-end">
      <div class="relative flex-1">
        <svg
          class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-primary/40"
          fill="none" stroke="currentColor"
          stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.35-4.35" />
        </svg>
        <input type="search" name="search"
          id="search"
          wire:model.live.debounce.300ms="search"
          placeholder="{{ $searchPlaceholder }}"
          aria-label="{{ $searchPlaceholder }}"
          class="w-full rounded-lg border border-primary/15 bg-white py-2.5 pl-10 pr-3 text-xs text-primary placeholder:text-primary/35 focus:border-primary/40 focus:outline-none" />
      </div>

      <div x-data="{ open: false }"
        class="relative w-10 flex-none">
        <button type="button" @click="open = !open"
          :aria-expanded="open"
          aria-label="Ubah urutan produk"
          class="flex h-[40px] w-full items-center justify-center rounded-lg border border-primary/15 bg-white text-primary transition-colors hover:border-primary/30 hover:bg-primary/5 cursor-pointer">
          @include('components.shop-sort-icon', ['sort' => $selectedSort])
        </button>

        <div x-show="open" x-transition
          @click.away="open = false"
          class="absolute right-0 z-20 mt-2 w-10 overflow-hidden rounded-lg border border-primary/10 bg-white shadow-lg"
          style="display: none;">
          @foreach ([
            'default' => ['title' => 'Default', 'icon' => 'default'],
            'price_asc' => ['title' => 'Murah ke Mahal', 'icon' => 'price_asc'],
            'price_desc' => ['title' => 'Mahal ke Murah', 'icon' => 'price_desc'],
            'name_asc' => ['title' => 'Dari A ke Z', 'icon' => 'name_asc'],
            'name_desc' => ['title' => 'Dari Z ke A', 'icon' => 'name_desc'],
          ] as $sortOption => $option)
            <button type="button"
              wire:click="setSort('{{ $sortOption }}')"
              wire:loading.attr="disabled"
              wire:target="setSort"
              @click="open = false"
              title="{{ $option['title'] }}"
              class="flex w-full items-center justify-center py-3 px-2 text-primary hover:bg-primary/5 cursor-pointer {{ $selectedSort === $sortOption ? 'bg-primary/5' : '' }}">
              @include('components.shop-sort-icon', ['sort' => $option['icon']])
            </button>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
