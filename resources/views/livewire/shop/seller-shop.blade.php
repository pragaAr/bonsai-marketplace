<div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="mb-8">
      <a href="{{ route('shop') }}" wire:navigate
        x-data="{ loading: false }" @click="loading = true"
        :class="loading ? 'opacity-80 pointer-events-none' :
            ''"
        class="inline-flex items-center gap-1.5 text-xs text-primary/60 hover:text-primary transition-colors">

        <x-icons.arrow-left x-show="!loading"
          aria-hidden="true" class="w-4 h-4" />

        <x-icons.spinner x-show="loading" x-cloak
          aria-hidden="true" class="h-4 w-4 text-current" />
        Kembali ke Koleksi
      </a>
    </div>

    <!-- Store Header -->
    <section
      class="bg-[#C65A3A] text-white rounded-2xl p-6 md:p-8 shadow-sm">
      <div
        class="flex flex-col md:flex-row md:items-center gap-5">
        <div
          class="w-20 h-20 rounded-2xl bg-cream/10 flex items-center justify-center shrink-0">
          <span class="text-3xl font-semibold">
            {{ Str::upper(Str::substr($seller->sellerRequest?->store_name ?? $seller->name, 0, 1)) }}
          </span>
        </div>
        <div class="flex-1">
          <p
            class="text-xs uppercase tracking-wider text-cream/60">
            Seller kami</p>
          <h1
            class="text-2xl md:text-3xl font-semibold mt-1">
            {{ $seller->sellerRequest?->store_name ?? $seller->name }}
          </h1>
          <p class="text-sm text-cream/70 mt-2">
            {{ $seller->sellerRequest?->notes ??
                'Koleksi tanaman dan perlengkapan pilihan dari seller kami.' }}
          </p>
        </div>
        <div class="flex gap-6 text-sm md:text-right">
          <div>
            <p class="text-xl font-semibold">
              {{ $productCount }}</p>
            <p class="text-cream/60 text-xs">Produk</p>
          </div>
          <div>
            <p class="text-xl font-semibold">Aktif</p>
            <p class="text-cream/60 text-xs">Status toko</p>
          </div>
        </div>
      </div>
    </section>

    <div class="flex items-end justify-between mt-10 mb-5">
      <div>
        <p
          class="text-xs uppercase tracking-wider text-accent font-semibold">
          Koleksi toko</p>
        <h2
          class="text-2xl font-semibold text-primary mt-1">
          Produk yang dijual</h2>
      </div>
    </div>

    <!-- Toolbar: Filters & Search -->
    <div class="mb-5 space-y-4">
      <div
        class="flex flex-wrap gap-4 xl:flex-row xl:items-center xl:justify-between">

        <!-- Category Pills -->
        <div
          class="flex w-full items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide whitespace-nowrap md:overflow-x-hidden xl:w-auto">
          <button type="button"
            wire:click="selectCategory('all')"
            wire:loading.attr="disabled"
            wire:target="selectCategory('all')"
            class="filter-btn flex-shrink-0 inline-flex items-center gap-1.5 whitespace-nowrap px-4 py-2 rounded-full text-xs font-medium border border-primary/20 hover:border-primary transition-colors duration-200 cursor-pointer {{ $category === 'all' ? 'active' : '' }}">

            <x-icons.spinner wire:loading
              wire:target="selectCategory('all')"
              class="h-3 w-3 text-current" />

            Semua Produk
          </button>

          @foreach ($categories as $categoryItem)
            <button type="button"
              wire:click="selectCategory('{{ $categoryItem->slug }}')"
              wire:loading.attr="disabled"
              wire:target="selectCategory('{{ $categoryItem->slug }}')"
              class="filter-btn flex-shrink-0 inline-flex items-center gap-1.5 whitespace-nowrap px-4 py-2 rounded-full text-xs font-medium border border-primary/20 hover:border-primary transition-colors duration-200 cursor-pointer {{ $category === $categoryItem->slug ? 'active' : '' }}">

              <x-icons.spinner wire:loading
                wire:target="selectCategory('{{ $categoryItem->slug }}')"
                class="h-3 w-3 text-current" />

              {{ $categoryItem->name }}
            </button>
          @endforeach
        </div>

        <!-- Search -->
        <div
          class="flex w-full gap-3 items-center xl:ml-auto xl:max-w-[560px] xl:justify-end">
          <div class="relative flex-1">
            <svg
              class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-primary/40"
              fill="none" stroke="currentColor"
              stroke-width="2" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="8" />
              <path d="m21 21-4.35-4.35" />
            </svg>
            <input type="search"
              wire:model.live.debounce.300ms="search"
              placeholder="Cari produk di toko ini..."
              aria-label="Cari produk di toko ini"
              class="w-full rounded-lg border border-primary/15 bg-white py-2.5 pl-10 pr-3 text-xs text-primary placeholder:text-primary/35 focus:border-primary/40 focus:outline-none" />
          </div>
        </div>
      </div>
    </div>

    <div wire:loading.flex
      wire:target="previousPage, nextPage, gotoPage"
      class="my-10 items-center justify-center text-center text-base font-medium text-primary/60 animate-pulse"
      aria-live="polite">
      Memuat...
    </div>

    @if ($products->isEmpty())
      <div
        class="bg-white rounded-xl border border-primary/5 shadow-sm text-center py-16">
        <p class="text-sm text-primary/50">
          {{ $search || $category !== 'all' ? 'Produk tidak ditemukan.' : 'Toko ini belum memiliki produk yang tersedia.' }}
        </p>
        @if ($search || $category !== 'all')
          <button type="button"
            wire:click="$set('category', 'all'); $set('search', '')"
            class="mt-3 inline-flex text-xs text-accent hover:underline cursor-pointer">
            Reset filter
          </button>
        @endif
      </div>
    @else
      <div wire:loading.class="hidden"
        wire:target="previousPage, nextPage, gotoPage"
        class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
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

      @if ($products->hasPages())
        <div class="mt-10 flex justify-center gap-2">
          <button type="button"
            wire:click="previousPage('{{ $products->getPageName() }}')"
            wire:loading.attr="disabled"
            wire:target="previousPage, nextPage, gotoPage"
            @if ($products->onFirstPage()) disabled @endif
            aria-label="Halaman sebelumnya"
            class="min-w-[44px] min-h-[44px] rounded-lg border border-primary/15 p-2.5 flex items-center justify-center hover:bg-primary/5 transition-colors disabled:opacity-30 disabled:pointer-events-none cursor-pointer">
            <x-icons.arrow-left class="w-4 h-4" />
          </button>

          @for ($i = 1; $i <= $products->lastPage(); $i++)
            <button type="button"
              wire:click="gotoPage({{ $i }}, '{{ $products->getPageName() }}')"
              wire:loading.attr="disabled"
              wire:target="previousPage, nextPage, gotoPage"
              aria-label="Halaman {{ $i }}"
              class="min-w-[44px] min-h-[44px] rounded-lg border border-primary/15 text-sm font-medium flex items-center justify-center hover:bg-primary/5 transition-colors cursor-pointer {{ $i === $products->currentPage() ? 'bg-primary text-cream border-primary' : '' }}">
              {{ $i }}
            </button>
          @endfor

          <button type="button"
            wire:click="nextPage('{{ $products->getPageName() }}')"
            wire:loading.attr="disabled"
            wire:target="previousPage, nextPage, gotoPage"
            @if (!$products->hasMorePages()) disabled @endif
            aria-label="Halaman berikutnya"
            class="min-w-[44px] min-h-[44px] rounded-lg border border-primary/15 p-2.5 flex items-center justify-center hover:bg-primary/5 transition-colors disabled:opacity-30 disabled:pointer-events-none cursor-pointer">
            <x-icons.arrow-right class="w-4 h-4" />
          </button>
        </div>
      @endif
    @endif
  </div>
</div>
