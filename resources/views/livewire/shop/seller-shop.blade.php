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
      class="bg-primary rounded-2xl p-6 md:p-8 text-cream shadow-sm">
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
            Toko bonsai</p>
          <h1
            class="text-2xl md:text-3xl font-semibold mt-1">
            {{ $seller->sellerRequest?->store_name ?? $seller->name }}
          </h1>
          <p class="text-sm text-cream/70 mt-2">
            Koleksi tanaman dan perlengkapan pilihan dari
            seller kami.
          </p>
        </div>
        <div class="flex gap-6 text-sm md:text-right">
          <div>
            <p class="text-xl font-semibold">
              {{ $products->count() }}</p>
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

    @if ($products->isEmpty())
      <div
        class="bg-white rounded-xl border border-primary/5 shadow-sm text-center py-16">
        <p class="text-sm text-primary/50">Toko ini belum
          memiliki produk yang tersedia.</p>
      </div>
    @else
      <div
        class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        @foreach ($products as $product)
          <a href="{{ route('product.detail', $product->slug) }}"
            wire:navigate
            class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <div class="overflow-hidden bg-primary/[0.02]">
              <img src="{{ $product->image_url }}"
                alt="{{ $product->name }} bonsai"
                class="w-full aspect-square object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy" />
            </div>
            <div class="p-4">
              <h3
                class="font-semibold text-primary text-sm line-clamp-1 group-hover:text-accent transition-colors">
                {{ $product->name }}
              </h3>
              <p
                class="text-primary font-bold text-sm mt-2">
                Rp
                {{ number_format($product->price, 0, ',', '.') }}
              </p>
            </div>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</div>
