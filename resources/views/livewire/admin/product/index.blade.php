<div class="space-y-6">
  <div
    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h1 class="text-2xl font-bold text-primary">
        {{ $title }}</h1>
      <p class="text-sm text-primary/60 mt-1">
        {{ $subTitle }}</p>
    </div>

    <div class="flex items-center gap-3">
      @if ($hasActiveFilter)
        <x-page.reset-button />
      @endif
      <x-page.filter-button />
    </div>
  </div>

  <div class="w-full mb-6">
    <x-forms.search-input
      placeholder="Cari produk atau penjual..." />
  </div>

  <div
    class="bg-white rounded-xl shadow-sm border border-primary/10">
    <div class="overflow-x-auto px-6 py-4">
      <table
        class="min-w-full text-sm border border-primary/10 border-collapse text-center">
        <thead class="bg-primary/5">
          <tr>
            <th
              class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
              Produk</th>
            <th
              class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
              Kategori</th>
            <th
              class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
              Penjual</th>
            <th
              class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
              Harga</th>
            <th
              class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
              Featured</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-primary/5">
          @forelse ($products as $product)
            <tr wire:key="product-{{ $product->id }}"
              class="border-b border-primary/10">
              <td
                class="px-5 py-3 text-primary border-r border-primary/10 font-medium">
                <div
                  class="flex items-center gap-3 text-left">
                  <div
                    class="w-12 h-12 rounded-lg bg-cream border border-primary/5 overflow-hidden flex-shrink-0">
                    <img src="{{ $product->image_url }}"
                      alt="{{ $product->name }}"
                      class="w-full h-full object-cover" />
                  </div>
                  <div>
                    <h3 class="font-medium text-primary">
                      {{ $product->name }}</h3>
                    <p
                      class="text-xs text-primary/60 line-clamp-1 mt-0.5">
                      {{ $product->short_description }}</p>
                  </div>
                </div>
              </td>
              <td
                class="px-5 py-3 text-primary border-r border-primary/10">
                {{ optional($product->category)->name ?? '-' }}
              </td>
              <td
                class="px-5 py-3 text-primary border-r border-primary/10">
                {{ optional($product->seller)->name ?? 'Tidak Ada' }}
              </td>
              <td
                class="px-5 py-3 text-primary text-right border-r border-primary/10">
                Rp
                {{ number_format($product->price, 0, ',', '.') }}
              </td>
              <td
                class="px-5 py-3 border-r border-primary/10">
                <button type="button"
                  wire:click="toggleFeatured({{ $product->id }})"
                  wire:loading.attr="disabled"
                  wire:target="toggleFeatured({{ $product->id }})"
                  role="switch"
                  aria-checked="{{ $product->featured ? 'true' : 'false' }}"
                  class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer {{ $product->featured ? 'bg-primary' : 'bg-primary/20' }} disabled:opacity-50">
                  <span
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $product->featured ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5"
                class="px-6 py-8 text-center text-primary/50">
                Belum ada produk yang disetujui.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-6 py-4">
      {{ $products->links('partials.custom-paginator') }}
    </div>
  </div>

  <div x-data="{ show: @entangle('showFilterModal') }" x-show="show"
    x-transition.opacity.duration.300ms
    style="display: none;"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    x-effect="show ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')">
    <div x-show="show" x-trap="show" x-transition
      class="bg-white rounded-2xl p-6 w-full max-w-md flex flex-col max-h-[85vh]">
      <x-modal.header
        wire:click="$set('showFilterModal', false)">Filter
        Produk</x-modal.header>

      <form wire:submit="filterList" x-data="{ pendingFilterSeller: '{{ $filterSeller }}', pendingFilterCategory: '{{ $filterCategory }}' }"
        x-on:submit="$wire.set('filterSeller', pendingFilterSeller); $wire.set('filterCategory', pendingFilterCategory)"
        x-on:filter-reset.window="pendingFilterSeller = ''; pendingFilterCategory = ''"
        class="space-y-4 my-4 flex-1 text-left">
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1">Penjual</label>
          <div x-data="tomSelect({ lazy: true, show: @entangle('showFilterModal'), value: '{{ $filterSeller }}', placeholder: 'Semua Penjual', ref: 'selectFilterSeller' })" wire:ignore
            class="w-full"
            x-on:change.stop="pendingFilterSeller = $event.target.value"
            x-on:filter-reset.window="pendingFilterSeller = ''; value = ''; tomselect && tomselect.clear(true)">
            <select x-ref="selectFilterSeller"
              class="w-full">
              <option value="">Semua Penjual</option>
              @foreach ($sellers as $seller)
                <option value="{{ $seller->id }}">
                  {{ $seller->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div>
          <label
            class="block text-sm font-medium text-primary mb-1">Kategori</label>
          <div x-data="tomSelect({ lazy: true, show: @entangle('showFilterModal'), value: '{{ $filterCategory }}', placeholder: 'Semua Kategori', ref: 'selectFilterCategory' })" wire:ignore
            class="w-full"
            x-on:change.stop="pendingFilterCategory = $event.target.value"
            x-on:filter-reset.window="pendingFilterCategory = ''; value = ''; tomselect && tomselect.clear(true)">
            <select x-ref="selectFilterCategory"
              class="w-full">
              <option value="">Semua Kategori</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                  {{ $category->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex gap-3 pt-4">
          <x-forms.cancel-button
            wire:click="$set('showFilterModal', false)">Batal</x-forms.cancel-button>
          <button type="submit" wire:target="filterList"
            wire:loading.attr="disabled"
            class="flex-1 px-4 py-2 bg-primary text-white font-semibold text-sm rounded-xl hover:shadow-lg transition-smooth cursor-pointer">
            <x-icons.spinner wire:loading
              wire:target="filterList"
              class="h-3.5 w-3.5 text-current" />
            Terapkan Filter
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
