<div x-data
  @shop-page-updated.window="window.scrollTo({ top: 0, behavior: 'smooth' })">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <!-- Shop Header -->
    <div class="mb-8 pb-4 border-b border-primary/10">
      <h1 class="text-3xl font-semibold text-primary">Koleksi
        Kami</h1>
      <p class="text-sm text-primary/50 mt-1">Bermacam produk
        dari komunitas untuk komunitas</p>
    </div>

    <!-- Toolbar: Filters, Search & Sort -->
    <div class="mb-5 space-y-4">
      <div
        class="flex flex-wrap gap-4 xl:flex-row xl:items-center xl:justify-between">

        <!-- Category Pills -->
        <div x-data="{ showAll: false }"
          class="flex w-full items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide whitespace-nowrap md:overflow-x-hidden xl:w-auto"
          :class="showAll ? 'md:!overflow-x-auto' : ''">
          @foreach ($categories as $cat)
            <button
              wire:click="selectCategory('{{ $cat->slug }}')"
              wire:loading.attr="disabled"
              wire:target="selectCategory('{{ $cat->slug }}')"
              class="filter-btn flex-shrink-0 inline-flex items-center gap-1.5 whitespace-nowrap px-4 py-2 rounded-full text-xs font-medium border border-primary/20 hover:border-primary transition-colors duration-200 cursor-pointer {{ $category === $cat->slug ? 'active' : '' }} {{ $loop->index >= 6 ? 'md:hidden' : '' }}"
              @if ($loop->index >= 6) :class="{ 'md:!flex': showAll }" @endif>

              <x-icons.spinner wire:loading
                wire:target="selectCategory('{{ $cat->slug }}')"
                class="h-3 w-3 text-current" />

              {{ $cat->name }}
            </button>
          @endforeach

          @if (count($categories) > 6)
            <button @click="showAll = !showAll"
              class="hidden md:inline-flex flex-shrink-0 items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold border border-primary/20 hover:border-primary transition-colors duration-200 bg-white text-primary cursor-pointer">
              <span
                x-text="showAll ? 'Lebih sedikit' : 'Selengkapnya'"></span>
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

        <!-- Bagian Search & Sort -->
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
              placeholder="Cari produk.."
              aria-label="Cari produk.."
              class="w-full rounded-lg border border-primary/15 bg-white py-2.5 pl-10 pr-3 text-xs text-primary placeholder:text-primary/35 focus:border-primary/40 focus:outline-none" />
          </div>

          <div x-data="{ open: false }"
            class="relative w-10 flex-none">
            <button type="button" @click="open = !open"
              :aria-expanded="open"
              aria-label="Ubah urutan produk"
              class="flex h-[40px] w-full items-center justify-center rounded-lg border border-primary/15 bg-white text-primary transition-colors hover:border-primary/30 hover:bg-primary/5 cursor-pointer">
              <span
                class="inline-flex items-center justify-center">
                @if ($sort === 'price_asc')
                  <x-icons.arrow-down-up
                    class="h-4 w-4 text-primary" />
                @elseif ($sort === 'price_desc')
                  <x-icons.arrow-up-down
                    class="h-4 w-4 text-primary" />
                @elseif ($sort === 'name_asc')
                  <x-icons.a-z
                    class="h-5 w-5 text-primary" />
                @elseif ($sort === 'name_desc')
                  <x-icons.z-a
                    class="h-5 w-5 text-primary" />
                @else
                  <x-icons.filter class="h-4 w-4" />
                @endif
              </span>
            </button>

            <!-- Dropdown menu -->
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
                  class="flex w-full items-center justify-center py-3 px-2 text-primary hover:bg-primary/5 cursor-pointer {{ $sort === $sortOption ? 'bg-primary/5' : '' }}">
                  @if ($option['icon'] === 'price_asc')
                    <x-icons.arrow-down-up
                      class="h-4 w-4" />
                  @elseif ($option['icon'] === 'price_desc')
                    <x-icons.arrow-up-down
                      class="h-4 w-4" />
                  @elseif ($option['icon'] === 'name_asc')
                    <x-icons.a-z class="h-5 w-5" />
                  @elseif ($option['icon'] === 'name_desc')
                    <x-icons.z-a class="h-5 w-5" />
                  @else
                    <x-icons.filter class="h-4 w-4" />
                  @endif
                </button>
              @endforeach
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Product Grid -->
    <div wire:loading.flex
      wire:target="previousPage, nextPage, gotoPage, setSort"
      class="my-10 items-center justify-center text-center text-base font-medium text-primary/60 animate-pulse"
      aria-live="polite">
      Memuat...
    </div>

    @if ($products->isEmpty())
      <div
        class="text-center py-20 bg-white rounded-xl border border-primary/5 shadow-sm">
        <svg class="w-16 h-16 text-primary/10 mx-auto mb-4"
          fill="none" stroke="currentColor"
          stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-base font-semibold text-primary">
          Produk tidak ditemukan</h3>
        <p
          class="text-xs text-primary/50 mt-1 max-w-xs mx-auto">
          Kami tidak dapat menemukan apa yang anda cari.
          <br>
          Coba ubah keyword yang anda masukkan.
        </p>
        <button
          wire:click="$set('category', 'All'); $set('search', ''); $set('sort', 'default')"
          class="mt-4 inline-flex text-xs text-accent hover:underline">
          Reset Filters
        </button>
      </div>
    @else
      <div wire:loading.class="hidden"
        wire:target="previousPage, nextPage, gotoPage, setSort"
        class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8 stagger-children">
        @foreach ($products as $product)
          <div
            class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full"
            wire:key="product-{{ $product->id }}">

            <!-- Product Gallery Click -->
            <a href="{{ route('product.detail', $product->slug) }}"
              wire:navigate class="block">
              <div
                class="product-img-wrapper overflow-hidden bg-primary/[0.02]">
                <img src="{{ $product->image_url }}"
                  alt="{{ $product->name }} bonsai"
                  class="product-image-aspect w-full object-cover transition-transform duration-500 hover:scale-105"
                  loading="lazy" />
              </div>
            </a>

            <!-- Product Specs -->
            <div class="p-4 flex flex-col flex-1">
              <a href="{{ route('product.detail', $product->slug) }}"
                wire:navigate class="block flex-1">
                <h3
                  class="font-semibold text-primary text-sm md:text-base leading-tight line-clamp-1 hover:text-accent transition-colors">
                  {{ $product->name }}</h3>
                <p
                  class="text-xs text-accent mt-1 line-clamp-1">
                  {{ Str::limit($product->short_description, 20, '…') }}
                </p>
                <p
                  class="text-primary font-bold text-sm mt-2">
                  Rp
                  {{ number_format($product->price, 0, ',', '.') }}
                </p>
              </a>

              <!-- Cart & Buy Actions -->
              <div
                class="flex flex-wrap flex-shrink gap-2 mt-4 pt-3 border-t border-primary/5">
                <!-- Add to Cart (Livewire Event dispatch) -->
                <x-cart-button :product="$product"
                  label="Keranjang"
                  spanClass="hidden sm:inline"
                  class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-primary text-cream text-xs py-2.5 px-3 rounded-lg transition-colors cursor-pointer hover:bg-opacity-90" />

                <x-buy-button :product="$product"
                  label="Beli"
                  spanClass="hidden sm:inline"
                  class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-[#C65A3A] text-white text-xs font-semibold py-2.5 px-3 rounded-lg hover:bg-[#A94B31] transition-colors" />
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination Controls -->
      @if ($products->hasPages())
        <div class="mt-12 flex justify-center gap-2">
          {{-- Previous Page Button --}}
          <button wire:click="previousPage"
            wire:loading.attr="disabled"
            wire:target="previousPage, nextPage, gotoPage"
            @if ($products->onFirstPage()) disabled @endif
            aria-label="Previous page"
            class="p-2.5 rounded-lg border border-primary/15 hover:bg-primary/5 transition-colors disabled:opacity-30 disabled:pointer-events-none min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer">
            <svg class="w-4 h-4" fill="none"
              stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round"
                d="M15 19l-7-7 7-7" />
            </svg>
          </button>

          {{-- Page Numbers --}}
          @for ($i = 1; $i <= $products->lastPage(); $i++)
            <button
              wire:click="gotoPage({{ $i }})"
              wire:loading.attr="disabled"
              wire:target="previousPage, nextPage, gotoPage"
              aria-label="Page {{ $i }}"
              class="page-btn min-w-[44px] min-h-[44px] rounded-lg border border-primary/15 text-sm font-medium transition-colors hover:bg-primary/5 flex items-center justify-center cursor-pointer {{ $i === $products->currentPage() ? 'active' : '' }}">
              {{ $i }}
            </button>
          @endfor

          {{-- Next Page Button --}}
          <button wire:click="nextPage"
            wire:loading.attr="disabled"
            wire:target="previousPage, nextPage, gotoPage"
            @if (!$products->hasMorePages()) disabled @endif
            aria-label="Next page"
            class="p-2.5 rounded-lg border border-primary/15 hover:bg-primary/5 transition-colors disabled:opacity-30 disabled:pointer-events-none min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer">
            <svg class="w-4 h-4" fill="none"
              stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round"
                d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      @endif
    @endif
  </div>
</div>
