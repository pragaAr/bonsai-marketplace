<div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <!-- Shop Header -->
    <div class="mb-8 pb-4 border-b border-primary/10">
      <h1 class="text-3xl font-semibold text-primary">Koleksi
        Kami</h1>
      <p class="text-sm text-primary/50 mt-1">Bermacam produk
        dari komunitas untuk komunitas</p>
    </div>

    <!-- Toolbar: Filters & Sort -->
    <div
      class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">

      <!-- Category Pills (Overflow-scroll on mobile, toggleable inline scroll on desktop) -->
      <div x-data="{ showAll: false }"
        class="flex gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide whitespace-nowrap md:overflow-x-hidden"
        :class="showAll ? 'md:!overflow-x-auto' : ''">
        @foreach ($categories as $cat)
          <button
            wire:click="selectCategory('{{ $cat }}')"
            wire:loading.attr="disabled"
            wire:target="selectCategory('{{ $cat }}')"
            class="filter-btn flex-shrink-0 inline-flex items-center gap-1.5 whitespace-nowrap px-4 py-2 rounded-full text-xs font-medium border border-primary/20 hover:border-primary transition-colors duration-200 cursor-pointer {{ $category === $cat ? 'active' : '' }} {{ $loop->index >= 5 ? 'md:hidden' : '' }}"
            @if ($loop->index >= 5) :class="{ 'md:!flex': showAll }" @endif>

            <svg wire:loading
              wire:target="selectCategory('{{ $cat }}')"
              class="h-3 w-3 animate-spin"
              xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12"
                cy="12" r="10" stroke="currentColor"
                stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            {{ $cat }}
          </button>
        @endforeach

        @if (count($categories) > 5)
          <button @click="showAll = !showAll"
            class="hidden md:inline-flex flex-shrink-0 items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold border border-primary/20 hover:border-primary transition-colors duration-200 bg-white text-primary cursor-pointer">
            <span
              x-text="showAll ? 'Show Less' : 'More Categories'"></span>
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

      <!-- Sort & Search Count -->
      <div
        class="flex items-center justify-between md:justify-end gap-4">

        <select wire:model.live="sort"
          class="bg-white border border-primary/15 rounded-lg px-3 py-2 text-xs text-primary focus:outline-none focus:border-primary/40 cursor-pointer">
          <option value="default">Default Sorting</option>
          <option value="price_asc">Price: Low to High
          </option>
          <option value="price_desc">Price: High to Low
          </option>
          <option value="name_asc">Alphabetical: A-Z
          </option>
          <option value="name_desc">Alphabetical: Z-A
          </option>
        </select>
      </div>
    </div>

    <!-- Active Search Info (if any) -->
    @if (!empty(trim($search)))
      <div class="mb-6 flex items-center gap-2">
        <span
          class="text-xs bg-primary/5 text-primary/60 px-3 py-1 rounded-full flex items-center gap-1.5">
          Search: "{{ $search }}"
          <button wire:click="$set('search', '')"
            class="hover:text-red-500 font-bold">×</button>
        </span>
      </div>
    @endif

    <!-- Product Grid -->
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
        <h3 class="text-base font-semibold text-primary">No
          Specimens Found</h3>
        <p
          class="text-xs text-primary/50 mt-1 max-w-xs mx-auto">
          We couldn't find any bonsai trees matching your
          current criteria. Try adjusting your filters or
          search term.</p>
        <button
          wire:click="$set('category', 'All'); $set('search', ''); $set('sort', 'default')"
          class="mt-4 inline-flex text-xs text-accent hover:underline">
          Reset Filters
        </button>
      </div>
    @else
      <div
        class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8 stagger-children">
        @foreach ($products as $product)
          <div
            class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full"
            wire:key="product-{{ $product->id }}">

            <!-- Product Gallery Click -->
            <a href="/shop/product/{{ $product->slug }}"
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
              <a href="/shop/product/{{ $product->slug }}"
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
            @if ($products->onFirstPage()) disabled @endif
            aria-label="Previous page"
            class="p-2.5 rounded-lg border border-primary/15 hover:bg-primary/5 transition-colors disabled:opacity-30 disabled:pointer-events-none min-w-[44px] min-h-[44px] flex items-center justify-center">
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
              aria-label="Page {{ $i }}"
              class="page-btn min-w-[44px] min-h-[44px] rounded-lg border border-primary/15 text-sm font-medium transition-colors hover:bg-primary/5 flex items-center justify-center {{ $i === $products->currentPage() ? 'active' : '' }}">
              {{ $i }}
            </button>
          @endfor

          {{-- Next Page Button --}}
          <button wire:click="nextPage"
            @if (!$products->hasMorePages()) disabled @endif
            aria-label="Next page"
            class="p-2.5 rounded-lg border border-primary/15 hover:bg-primary/5 transition-colors disabled:opacity-30 disabled:pointer-events-none min-w-[44px] min-h-[44px] flex items-center justify-center">
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
