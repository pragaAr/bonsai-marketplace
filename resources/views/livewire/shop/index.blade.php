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
    <x-shop-toolbar
      :categories="$categories"
      :selected-category="$category"
      :selected-sort="$sort"
      :show-all-categories="true"
      search-placeholder="Cari produk.." />

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
        class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8 stagger-children">
        @foreach ($products as $product)
          <x-product-card :product="$product"
            :key="$product->id" />
        @endforeach
      </div>

      <x-pagination :paginator="$products" />
    @endif
  </div>
</div>
