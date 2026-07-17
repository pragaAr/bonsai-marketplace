<div>
  <div class="space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-primary">
          {{ $title ?: 'Produk Saya' }}
        </h1>
        <p class="text-sm text-primary/60 mt-1">
          {{ $subTitle ?: 'Kelola dan pantau semua produk yang Anda pasarkan' }}
        </p>
      </div>
      <div class="flex items-center gap-3">
        @if ($hasActiveFilter)
          <x-page.reset-button />
        @endif

        <x-page.filter-button />

        <a href="{{ route('seller.products.create') }}"
          wire:navigate
          class="inline-flex items-center justify-center px-4 py-2 bg-primary text-cream hover:bg-primary/95 font-medium rounded-lg text-sm transition duration-150">
          <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4.5 w-4.5 mr-2" fill="none"
            viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round"
              stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Produk Baru
        </a>
      </div>
    </div>

    <div class="w-full mb-6">
      <x-forms.search-input
        placeholder="Cari nama produk..." />
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
                Harga</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Stok</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Status</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-primary/10">
                Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5">
            @forelse($products as $product)
              <tr class="border-b border-primary/10"
                wire:key="{{ $product->id }}">

                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 font-medium">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-12 h-12 rounded-lg bg-cream border border-primary/5 overflow-hidden flex-shrink-0">
                      <img src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover" />
                    </div>
                    <div class="text-left">
                      <h3 class="font-medium text-primary">
                        {{ $product->name }}</h3>
                      <p class="text-xs text-primary/60 line-clamp-1 mt-0.5"
                        title="{{ $product->short_description }}">
                        {{ $product->short_description }}
                      </p>
                    </div>
                  </div>
                </td>

                <!-- Category -->
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  <span
                    class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-primary/5 text-primary/80">
                    {{ $product->category->name }}
                  </span>
                </td>

                <!-- Price -->
                <td
                  class="px-5 py-3 text-primary text-right border-r border-primary/10">
                  Rp
                  {{ number_format($product->price, 0, ',', '.') }}
                </td>

                <!-- Stock -->
                <td
                  class="px-5 py-3 border-r border-primary/10">
                  <span
                    class="{{ $product->stock > 0 ? 'text-primary' : 'text-red-500 font-semibold' }}">
                    {{ $product->stockLabel() }}
                  </span>
                </td>

                <!-- Status -->
                <td
                  class="px-5 py-3 border-r border-primary/10">
                  @if ($product->status === 'draft')
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                      Draft
                    </span>
                  @elseif($product->status === 'pending')
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                      Menunggu Review
                    </span>
                  @elseif($product->status === 'approved')
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                      Disetujui
                    </span>
                  @elseif($product->status === 'rejected')
                    <div
                      class="group relative inline-block cursor-help">
                      <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                        Ditolak
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-3 w-3 ml-1"
                          fill="none" viewBox="0 0 24 24"
                          stroke="currentColor">
                          <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </span>
                      <div
                        class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-primary text-cream text-xs rounded-lg p-3 shadow-lg border border-cream/10">
                        <p
                          class="font-semibold text-accent mb-1">
                          Alasan Penolakan:</p>
                        <p class="leading-relaxed">
                          {{ $product->rejection_reason ?? 'Tidak ada alasan spesifik.' }}
                        </p>
                        <div
                          class="absolute top-full left-1/2 transform -translate-x-1/2 border-solid border-t-primary border-t-8 border-x-transparent border-x-8 border-b-0">
                        </div>
                      </div>
                    </div>
                  @endif
                </td>

                <td
                  class="px-5 py-3 text-center whitespace-nowrap space-x-3">
                  <a href="{{ route('seller.products.edit', $product->id) }}"
                    wire:navigate
                    class="inline-flex items-center gap-1 text-xs text-orange-600 hover:text-orange-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Edit">
                    <x-icons.edit />
                  </a>
                  <button
                    wire:click="confirmDelete({{ $product->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Hapus">
                    <x-icons.spinner wire:loading
                      wire:target="confirmDelete({{ $product->id }})"
                      class="h-3.5 w-3.5 text-current" />

                    <span wire:loading.remove
                      wire:target="confirmDelete({{ $product->id }})">
                      <x-icons.delete />
                    </span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6"
                  class="px-6 py-8 text-center text-primary/50">
                  <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-10 w-10 mx-auto text-primary/30 mb-3"
                    viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                      d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                    </path>
                    <polyline points="14 2 14 8 20 8">
                    </polyline>
                    <line x1="8" y1="13"
                      x2="16" y2="13"
                      stroke-dasharray="2 2"></line>
                    <line x1="8" y1="17"
                      x2="12" y2="17"
                      stroke-dasharray="2 2"></line>
                  </svg>

                  Belum ada produk terdaftar.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="px-6 py-4">
        {{ $products->links('partials.custom-paginator') }}
      </div>
    </div>
  </div>

  <!-- Filter Modal -->
  <div x-data="{ show: @entangle('showFilterModal') }" x-show="show"
    x-transition.opacity.duration.300ms
    style="display: none;"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    x-effect="show ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')">

    <div x-show="show" x-trap="show"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-8"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 translate-y-8"
      class="bg-white rounded-2xl p-6 w-full max-w-md flex flex-col max-h-[85vh]">

      <x-modal.header
        wire:click="$set('showFilterModal', false)">
        Filter Permintaan Penjual
      </x-modal.header>

      <form wire:submit="filterList"
        x-data="{ pendingFilterStatus: '{{ $filterStatus }}' }"
        x-on:submit="$wire.set('filterStatus', pendingFilterStatus)"
        x-on:filter-reset.window="pendingFilterStatus = ''"
        class="space-y-4 my-4 flex-1 text-left">
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1">
            Status Pengajuan
          </label>
          <div x-data="tomSelect({ lazy: true, show: @entangle('showFilterModal'), value: '{{ $filterStatus }}', placeholder: 'Semua Status', ref: 'selectFilterStatus' })" wire:ignore
            class="w-full"
            x-on:change.stop="pendingFilterStatus = $event.target.value"
            x-on:filter-reset.window="value = ''; tomselect && tomselect.clear(true)">
            <select x-ref="selectFilterStatus"
              class="w-full">
              <option value=""disabled>Semua Status
              </option>
              <option value="pending">Menunggu Persetujuan
              </option>
              <option value="approved">Disetujui</option>
              <option value="rejected">Ditolak</option>
              <option value="draft">Draft (Seller)
              </option>
            </select>
          </div>
        </div>

        <div class="flex gap-3 pt-4">
          <x-forms.cancel-button
            wire:click="$set('showFilterModal', false)">
            Batal
          </x-forms.cancel-button>
          <button type="submit" wire:target="filterList"
            wire:loading.attr="disabled"
            class="flex-1 px-4 py-2 bg-primary text-white font-semibold text-sm rounded-xl hover:shadow-lg transition-smooth cursor-pointer gap-1">
            <x-icons.spinner wire:loading
              wire:target="filterList"
              class="h-3.5 w-3.5 text-current" />
            Terapkan Filter
          </button>
        </div>
      </form>
    </div>
  </div>

  <x-page.delete-modal :show="'showDeleteModal'" action="delete"
    title="Konfirmasi Hapus Produk"
    message="Yakin ingin menghapus produk ini?"
    text="Tindakan ini tidak dapat dibatalkan dan semua data produk serta gambar akan dihapus permanen."
    confirmText="Ya, Hapus" />

</div>
