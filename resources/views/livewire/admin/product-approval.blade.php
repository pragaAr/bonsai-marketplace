<div class="space-y-6">
  <!-- Header -->
  <div>
    <h1 class="text-2xl font-bold text-primary">Persetujuan Produk</h1>
    <p class="text-sm text-primary/60 mt-1">Tinjau dan kelola kelayakan produk yang diajukan oleh penjual</p>
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
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk atau penjual..."
          class="w-full pl-10 pr-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
      </div>

      <div class="w-full md:w-auto flex flex-wrap gap-3 items-center">
        <div class="relative w-full md:w-48">
          <select wire:model.live="filterStatus"
            class="w-full px-3 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-white appearance-none">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Persetujuan</option>
            <option value="approved">Disetujui</option>
            <option value="rejected">Ditolak</option>
            <option value="draft">Draft (Seller)</option>
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

  <!-- Table Card -->
  <div class="bg-white rounded-xl shadow-sm border border-primary/10 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-sm">
        <thead>
          <tr class="bg-cream/20 border-b border-primary/10 text-primary/60 font-semibold">
            <th class="px-6 py-4">Produk</th>
            <th class="px-6 py-4">Kategori</th>
            <th class="px-6 py-4">Penjual</th>
            <th class="px-6 py-4">Harga</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-primary/5">
          @forelse($products as $prod)
            <tr class="hover:bg-cream/5 transition duration-150">
              <!-- Thumbnail & Name -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-12 h-12 rounded-lg bg-cream border border-primary/5 overflow-hidden flex-shrink-0">
                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover" />
                  </div>
                  <div>
                    <h3 class="font-medium text-primary">{{ $prod->name }}</h3>
                    <p class="text-xs text-primary/60 line-clamp-1 mt-0.5">{{ $prod->short_description }}</p>
                  </div>
                </div>
              </td>

              <!-- Category -->
              <td class="px-6 py-4">
                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-primary/5 text-primary/80">
                  {{ $prod->category->name }}
                </span>
              </td>

              <!-- Seller -->
              <td class="px-6 py-4 text-primary/80">
                {{ optional($prod->seller)->name ?? 'Tidak Ada' }}
              </td>

              <!-- Price -->
              <td class="px-6 py-4 font-semibold text-primary">
                Rp {{ number_format($prod->price, 0, ',', '.') }}
              </td>

              <!-- Status -->
              <td class="px-6 py-4">
                @if($prod->status === 'draft')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                    Draft
                  </span>
                @elseif($prod->status === 'pending')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    Menunggu Review
                  </span>
                @elseif($prod->status === 'approved')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    Disetujui
                  </span>
                @elseif($prod->status === 'rejected')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                    Ditolak
                  </span>
                @endif
              </td>

              <!-- Action button -->
              <td class="px-6 py-4 text-right">
                <button wire:click="openDetail({{ $prod->id }})"
                  class="inline-flex items-center justify-center px-3 py-1.5 bg-primary/5 hover:bg-primary text-primary hover:text-cream rounded-md text-xs font-medium transition duration-150">
                  Tinjau Detail
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center text-primary/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-primary/30 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Antrean produk kosong. Tidak ada produk yang perlu ditinjau.
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

  <!-- Detail Modal -->
  @if($showDetailModal && $selectedProduct)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
      <div class="fixed inset-0 bg-primary/50 backdrop-blur-sm transition-opacity"></div>
      <div class="relative w-full max-w-4xl mx-auto my-6 px-4 z-50">
        <div class="bg-white rounded-xl shadow-2xl border border-primary/10 overflow-hidden max-h-[90vh] flex flex-col">
          <!-- Modal Header -->
          <div class="flex items-center justify-between p-5 border-b border-primary/10 bg-cream/10 flex-shrink-0">
            <div>
              <span class="text-accent text-[10px] font-bold uppercase tracking-wider bg-accent/5 px-2 py-0.5 rounded">
                {{ $selectedProduct->category->name }}
              </span>
              <h3 class="text-lg font-bold text-primary mt-1">Review: {{ $selectedProduct->name }}</h3>
            </div>
            <button wire:click="$set('showDetailModal', false)" class="text-primary/60 hover:text-primary transition duration-150">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Modal Body (Scrollable) -->
          <div class="p-6 overflow-y-auto space-y-6 flex-1 text-sm text-primary">
            <!-- Row 1: Images Gallery & Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
              <!-- Images Gallery -->
              <div class="space-y-3">
                <p class="text-xs font-semibold text-primary/50 uppercase tracking-wider">Gambar Produk</p>
                <div class="grid grid-cols-2 gap-3">
                  @forelse($selectedProduct->getMedia('images') as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" class="block aspect-square rounded-lg border border-primary/10 overflow-hidden bg-cream hover:opacity-90 transition duration-150">
                      <img src="{{ $media->getUrl() }}" class="w-full h-full object-cover" />
                    </a>
                  @empty
                    <!-- Fallback if no images found -->
                    <div class="col-span-2 aspect-square rounded-lg border border-dashed border-primary/20 flex flex-col items-center justify-center text-primary/40">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      Gambar Default
                    </div>
                  @endforelse
                </div>
              </div>

              <!-- Basic details -->
              <div class="space-y-4 bg-cream/10 border border-primary/5 rounded-xl p-5">
                <p class="text-xs font-semibold text-primary/50 uppercase tracking-wider border-b border-primary/5 pb-1">Detail Listing</p>
                <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                  <div>
                    <span class="text-xs text-primary/60 block">Harga</span>
                    <span class="font-bold text-base text-primary">Rp {{ number_format($selectedProduct->price, 0, ',', '.') }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Stok</span>
                    <span class="font-bold text-base text-primary">{{ $selectedProduct->stockLabel() }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Penjual</span>
                    <span class="font-medium text-primary">{{ optional($selectedProduct->seller)->name }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Email Penjual</span>
                    <span class="text-xs text-primary/80 truncate block">{{ optional($selectedProduct->seller)->email }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">No. WhatsApp</span>
                    <span class="font-medium text-primary">{{ optional($selectedProduct->seller)->whatsapp ?: '-' }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Diajukan Pada</span>
                    <span class="text-xs text-primary/80 block">{{ $selectedProduct->created_at->format('d M Y, H:i') }}</span>
                  </div>
                </div>

                @if(count($selectedProduct->tags) > 0)
                  <div class="mt-4 border-t border-primary/5 pt-3">
                    <span class="text-xs text-primary/60 block mb-1.5">Tags Produk</span>
                    <div class="flex flex-wrap gap-1.5">
                      @foreach($selectedProduct->tags as $tag)
                        <span class="px-2 py-0.5 rounded bg-primary text-cream text-[10px]">#{{ $tag->name }}</span>
                      @endforeach
                    </div>
                  </div>
                @endif
              </div>
            </div>

            <!-- Description -->
            <div class="space-y-2">
              <p class="text-xs font-semibold text-primary/50 uppercase tracking-wider">Deskripsi Produk</p>
              <div class="bg-cream/10 border border-primary/5 rounded-xl p-5 leading-relaxed">
                <p class="font-medium text-primary text-sm">{{ $selectedProduct->short_description }}</p>
                <div class="h-px bg-primary/5 my-2.5"></div>
                <p class="text-primary/80 text-xs whitespace-pre-line">{{ $selectedProduct->description }}</p>
              </div>
            </div>

            <!-- Category Specific details (Polymorphic spec) -->
            <div class="space-y-2">
              <p class="text-xs font-semibold text-primary/50 uppercase tracking-wider">Spesifikasi Tambahan (Polymorphic)</p>
              <div class="bg-white border border-primary/10 rounded-xl p-5 grid grid-cols-2 sm:grid-cols-3 gap-4">
                @if($selectedProduct->isPlant())
                  <div>
                    <span class="text-xs text-primary/60 block">Spesies / Scientific Name</span>
                    <span class="font-medium text-primary mt-0.5 block italic">{{ optional($selectedProduct->productable->species)->scientific_name }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Nama Umum / Common Name</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ optional($selectedProduct->productable->species)->common_name ?: '-' }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Tingkat Perawatan</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->care_level }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Pencahayaan</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->light }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Penyiraman</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->watering }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Ukuran Pot</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->pot_size ?: '-' }}</span>
                  </div>
                @elseif($selectedProduct->isMedia())
                  <div>
                    <span class="text-xs text-primary/60 block">Tipe Media Tanam</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->type }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Berat Bersih</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->weight ?: '-' }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Volume</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->volume ?: '-' }}</span>
                  </div>
                @elseif($selectedProduct->isPot())
                  <div>
                    <span class="text-xs text-primary/60 block">Bahan Pot</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->material }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Bentuk Pot</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->shape }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Dimensi Pot</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->dimensions }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Warna Pot</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->color }}</span>
                  </div>
                @elseif($selectedProduct->isFertilizer())
                  <div>
                    <span class="text-xs text-primary/60 block">Tipe Pupuk</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->type }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Formulasi</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->form }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Berat / Kemasan</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->weight }}</span>
                  </div>
                @elseif($selectedProduct->isTool())
                  <div>
                    <span class="text-xs text-primary/60 block">Bahan Alat</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->material }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Merek</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->brand }}</span>
                  </div>
                  <div>
                    <span class="text-xs text-primary/60 block">Berat Alat</span>
                    <span class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->weight ?: '-' }}</span>
                  </div>
                @endif
              </div>
            </div>

            <!-- Rejection Info (If Rejected in history) -->
            @if($selectedProduct->status === 'rejected')
              <div class="bg-red-50 border border-red-200 rounded-xl p-5 space-y-1">
                <span class="text-xs text-red-600 block uppercase font-bold tracking-wider">Alasan Penolakan Sebelumnya</span>
                <p class="text-red-900 font-medium">{{ $selectedProduct->rejection_reason }}</p>
              </div>
            @elseif($selectedProduct->status === 'approved')
              <div class="bg-green-50 border border-green-200 rounded-xl p-5 flex items-center justify-between">
                <div>
                  <span class="text-xs text-green-600 block uppercase font-bold tracking-wider">Info Disetujui</span>
                  <p class="text-green-900 font-medium">Disetujui oleh: {{ optional($selectedProduct->approvedBy)->name }}</p>
                </div>
                <div class="text-right">
                  <span class="text-xs text-primary/55 block">Tanggal Approval</span>
                  <span class="text-xs text-green-950 font-bold block">{{ $selectedProduct->approved_at ? $selectedProduct->approved_at->format('d M Y, H:i') : '-' }}</span>
                </div>
              </div>
            @endif

            <!-- Reject input area -->
            @if($isRejecting)
              <div class="bg-cream/20 border border-primary/20 rounded-xl p-5 space-y-3">
                <label for="rejectionReason" class="block text-xs font-bold text-red-600 uppercase">Mengapa Produk Ini Ditolak? <span class="text-red-500">*</span></label>
                <textarea id="rejectionReason" wire:model="rejectionReason" rows="3" placeholder="Tulis alasan penolakan secara jelas agar dipahami penjual..."
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-red-500 bg-white"></textarea>
                @error('rejectionReason') <span class="text-xs text-red-500 font-medium block">{{ $message }}</span> @enderror
                
                <div class="flex justify-end gap-2 pt-2">
                  <button type="button" wire:click="cancelReject"
                    class="px-3 py-1.5 bg-white hover:bg-cream/40 text-primary border border-primary/20 rounded-md text-xs font-semibold transition duration-150">
                    Batal
                  </button>
                  <button type="button" wire:click="reject"
                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-md text-xs font-semibold shadow transition duration-150">
                    Kirim Penolakan
                  </button>
                </div>
              </div>
            @endif
          </div>

          <!-- Modal Footer (Actions) -->
          <div class="p-5 border-t border-primary/10 bg-cream/10 flex items-center justify-between flex-shrink-0">
            <div>
              <button wire:click="$set('showDetailModal', false)"
                class="px-4 py-2 bg-white hover:bg-cream text-primary border border-primary/20 font-medium rounded-lg text-sm transition duration-150">
                Tutup
              </button>
            </div>
            
            @if($selectedProduct->status === 'pending' && !$isRejecting)
              <div class="flex items-center gap-3">
                <button type="button" wire:click="startReject"
                  class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-medium rounded-lg text-sm transition duration-150">
                  Tolak Listing
                </button>
                <button type="button" wire:click="approve"
                  class="px-4 py-2 bg-accent hover:bg-accent/95 text-white font-medium rounded-lg text-sm shadow transition duration-150">
                  Setujui & Tayangkan
                </button>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
