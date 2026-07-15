<div class="space-y-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-primary">Daftar Produk Saya</h1>
      <p class="text-sm text-primary/60 mt-1">Kelola dan pantau semua produk yang Anda pasarkan</p>
    </div>
    <div>
      <a href="{{ route('seller.products.create') }}" wire:navigate
        class="inline-flex items-center justify-center px-4 py-2 bg-primary text-cream hover:bg-primary/95 font-medium rounded-lg text-sm transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Produk Baru
      </a>
    </div>
  </div>

  <!-- Search & Filter Card -->
  <div class="bg-white rounded-xl shadow-sm border border-primary/10 p-5">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
      <div class="w-full md:w-1/2 relative">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary/40">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </span>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama produk..."
          class="w-full pl-10 pr-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
      </div>

      <div class="w-full md:w-auto flex flex-wrap gap-3 items-center">
        <div class="relative w-full md:w-44">
          <select wire:model.live="filterStatus"
            class="w-full px-3 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-white appearance-none">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="pending">Menunggu Persetujuan</option>
            <option value="approved">Disetujui</option>
            <option value="rejected">Ditolak</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-primary/50">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
            </svg>
          </div>
        </div>

        @if($hasActiveFilter)
          <button wire:click="resetFilters"
            class="inline-flex items-center text-sm text-red-600 hover:text-red-700 font-medium px-3 py-2">
            Reset Filter
          </button>
        @endif
      </div>
    </div>
  </div>

  <!-- Products List Card -->
  <div class="bg-white rounded-xl shadow-sm border border-primary/10 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-sm">
        <thead>
          <tr class="bg-cream/20 border-b border-primary/10 text-primary/60 font-semibold">
            <th class="px-6 py-4">Produk</th>
            <th class="px-6 py-4">Kategori</th>
            <th class="px-6 py-4">Harga</th>
            <th class="px-6 py-4">Stok</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-primary/5">
          @forelse($products as $product)
            <tr class="hover:bg-cream/5 transition duration-150">
              <!-- Product info (Thumbnail & Name) -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-12 h-12 rounded-lg bg-cream border border-primary/5 overflow-hidden flex-shrink-0">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                  </div>
                  <div>
                    <h3 class="font-medium text-primary">{{ $product->name }}</h3>
                    <p class="text-xs text-primary/60 line-clamp-1 mt-0.5">{{ $product->short_description }}</p>
                  </div>
                </div>
              </td>

              <!-- Category -->
              <td class="px-6 py-4">
                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-primary/5 text-primary/80">
                  {{ $product->category->name }}
                </span>
              </td>

              <!-- Price -->
              <td class="px-6 py-4 font-semibold text-primary">
                Rp {{ number_format($product->price, 0, ',', '.') }}
              </td>

              <!-- Stock -->
              <td class="px-6 py-4">
                <span class="{{ $product->stock > 0 ? 'text-primary' : 'text-red-500 font-semibold' }}">
                  {{ $product->stockLabel() }}
                </span>
              </td>

              <!-- Status -->
              <td class="px-6 py-4">
                @if($product->status === 'draft')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                    Draft
                  </span>
                @elseif($product->status === 'pending')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    Menunggu Review
                  </span>
                @elseif($product->status === 'approved')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    Disetujui
                  </span>
                @elseif($product->status === 'rejected')
                  <div class="group relative inline-block cursor-help">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                      Ditolak
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </span>
                    <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-primary text-cream text-xs rounded-lg p-3 shadow-lg border border-cream/10">
                      <p class="font-semibold text-accent mb-1">Alasan Penolakan:</p>
                      <p class="leading-relaxed">{{ $product->rejection_reason ?? 'Tidak ada alasan spesifik.' }}</p>
                      <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-solid border-t-primary border-t-8 border-x-transparent border-x-8 border-b-0"></div>
                    </div>
                  </div>
                @endif
              </td>

              <!-- Actions -->
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('seller.products.edit', $product->id) }}" wire:navigate
                    class="p-1 text-primary/60 hover:text-accent transition duration-150" title="Edit Produk">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </a>
                  <button wire:click="confirmDelete({{ $product->id }})"
                    class="p-1 text-primary/60 hover:text-red-600 transition duration-150" title="Hapus Produk">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center text-primary/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-primary/30 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Belum ada produk terdaftar. Mulai dengan menambahkan produk baru!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
      <div class="px-6 py-4 border-t border-primary/10">
        {{ $products->links() }}
      </div>
    @endif
  </div>

  <!-- Delete Modal -->
  @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
      <div class="fixed inset-0 bg-primary/50 backdrop-blur-sm transition-opacity"></div>
      <div class="relative w-auto max-w-md mx-auto my-6 z-50">
        <div class="bg-white rounded-xl shadow-xl border border-primary/10 overflow-hidden">
          <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full text-red-600 mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <h3 class="text-lg font-bold text-center text-primary mb-2">Hapus Produk?</h3>
            <p class="text-sm text-center text-primary/70 mb-6">
              Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan dan semua data produk serta gambar akan dihapus permanen.
            </p>
            <div class="flex justify-end gap-3">
              <button wire:click="$set('showDeleteModal', false)"
                class="px-4 py-2 bg-cream/50 hover:bg-cream text-primary font-medium rounded-lg text-sm transition duration-150">
                Batal
              </button>
              <button wire:click="delete"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm transition duration-150">
                Ya, Hapus
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
