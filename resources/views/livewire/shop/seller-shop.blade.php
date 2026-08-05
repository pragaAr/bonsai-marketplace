<div x-data
  @shop-page-updated.window="document.getElementById('scrollTarget')?.scrollIntoView({ behavior: 'smooth' })">
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
      class="bg-gradient-to-br from-[#C65A3A] via-[#B24D30] to-[#8C3A22] text-white rounded-2xl p-6 md:p-8 shadow-md relative overflow-hidden">

      <div
        class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl pointer-events-none">
      </div>

      <div
        class="flex flex-col sm:flex-row items-start sm:items-center gap-5 relative z-10">

        <!-- Avatar / Logo Inisial -->
        <div
          class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-md ring-1 ring-white/20 flex items-center justify-center shrink-0 shadow-inner">
          <span
            class="text-3xl font-bold text-white tracking-wider">
            {{ Str::upper(Str::substr($seller->sellerRequest?->store_name ?? $seller->name, 0, 1)) }}
          </span>
        </div>

        <!-- Info Toko & Statistik -->
        <div
          class="flex flex-col md:flex-row md:items-center justify-between gap-4 flex-1 min-w-0 w-full">

          <div class="min-w-0 flex-1">
            <h1
              class="text-2xl md:text-3xl font-bold tracking-tight truncate">
              {{ $seller->sellerRequest?->store_name ?? $seller->name }}
            </h1>
            <p
              class="text-sm text-white/80 mt-1.5 line-clamp-2 max-w-xl leading-relaxed">
              {{ $seller->sellerRequest?->notes ?? 'Koleksi tanaman dan perlengkapan pilihan dari seller kami.' }}
            </p>
          </div>

          <div
            class="flex items-center gap-6 pt-4 md:pt-0 border-t border-white/10 md:border-t-0 shrink-0">
            <div class="flex flex-col">
              <span class="text-xl font-bold leading-none">
                {{ $productCount }}
              </span>
              <span
                class="text-xs text-white/70 mt-1">Produk</span>
            </div>

            <div class="h-8 w-[1px] bg-white/15"></div>

            <div class="flex flex-col">
              <div class="flex items-center gap-1.5">
                <span
                  class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span
                  class="text-base font-semibold leading-none">Aktif</span>
              </div>
              <span
                class="text-xs text-white/70 mt-1">Status
                Toko</span>
            </div>
          </div>

        </div>
      </div>
    </section>

    <div
      class="flex items-end justify-between mt-10 mb-5 scroll-mt-24"
      id="scrollTarget">
      <div>
        <p
          class="text-xs uppercase tracking-wider text-accent font-semibold">
          Koleksi toko</p>
        <h2
          class="text-2xl font-semibold text-primary mt-1">
          Produk yang dijual</h2>
      </div>
    </div>

    <!-- Toolbar: Filters, Search & Sort -->
    <x-shop-toolbar :categories="$categories" :selected-category="$category"
      :selected-sort="$sort"
      search-placeholder="Cari produk di toko ini..." />

    <!-- Product Grid -->
    <div wire:loading.flex
      wire:target="previousPage, nextPage, gotoPage, setSort"
      class="my-10 items-center justify-center text-center text-base font-medium text-primary/60 animate-pulse"
      aria-live="polite">
      Memuat...
    </div>

    @if ($products->isEmpty())
      <x-empty-products />
    @else
      <div wire:loading.class="hidden"
        wire:target="previousPage, nextPage, gotoPage, setSort"
        class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        @foreach ($products as $product)
          <x-product-card :product="$product"
            :key="$product->id" />
        @endforeach
      </div>

      <x-pagination :paginator="$products" />
    @endif
  </div>
</div>
