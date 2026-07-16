<div>
  <div class="space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-primary">
          {{ $title ?: 'Persetujuan Produk' }}
        </h1>
        <p class="text-sm text-primary/60 mt-1">
          {{ $subTitle ?: 'Tinjau produk yang diajukan oleh penjual' }}
        </p>
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
                Status</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-primary/10">
                Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5">
            @forelse($products as $prod)
              <tr class="border-b border-primary/10"
                wire:key="{{ $prod->id }}">

                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 font-medium">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-12 h-12 rounded-lg bg-cream border border-primary/5 overflow-hidden flex-shrink-0">
                      <img src="{{ $prod->image_url }}"
                        alt="{{ $prod->name }}"
                        class="w-full h-full object-cover" />
                    </div>
                    <div class="text-left">
                      <h3 class="font-medium text-primary">
                        {{ $prod->name }}</h3>
                      <p class="text-xs text-primary/60 line-clamp-1 mt-0.5"
                        title="{{ $prod->short_description }}">
                        {{ $prod->short_description }}</p>
                    </div>
                  </div>
                </td>

                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  <span
                    class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-primary/5 text-primary/80">
                    {{ $prod->category->name }}
                  </span>
                </td>

                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ optional($prod->seller)->name ?? 'Tidak Ada' }}
                </td>

                <td
                  class="px-5 py-3 text-primary text-right border-r border-primary/10">
                  Rp
                  {{ number_format($prod->price, 0, ',', '.') }}
                </td>

                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  @if ($prod->status === 'draft')
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                      Draft
                    </span>
                  @elseif($prod->status === 'pending')
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                      Menunggu Review
                    </span>
                  @elseif($prod->status === 'approved')
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                      Disetujui
                    </span>
                  @elseif($prod->status === 'rejected')
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                      Ditolak
                    </span>
                  @endif
                </td>

                <td
                  class="px-5 py-3 text-center whitespace-nowrap space-x-3">
                  <button
                    wire:click="openDetail({{ $prod->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-primary hover:text-accent font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Lihat Detail">
                    <x-icons.spinner wire:loading
                      wire:target="openDetail({{ $prod->id }})"
                      class="h-3.5 w-3.5 text-current" />
                    <span wire:loading.remove
                      wire:target="openDetail({{ $prod->id }})">
                      <x-icons.eye class="h-4 w-4" />
                    </span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6"
                  class="px-5 py-8 text-center text-primary/50">
                  <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-10 w-10 mx-auto text-primary/30 mb-3"
                    fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                  </svg>
                  Antrean produk kosong. Tidak ada produk
                  yang perlu ditinjau.
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

  <!-- Detail Modal -->
  <div x-data="{ show: @entangle('showDetailModal') }" x-show="show"
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
      class="bg-white rounded-2xl p-6 w-full max-w-4xl flex flex-col max-h-[85vh]">

      <x-modal.header
        wire:click="$set('showDetailModal', false)">
        Review Listing Produk
      </x-modal.header>

      @if ($selectedProduct)
        <div
          class="space-y-6 overflow-y-auto pr-2 flex-1 sidebar-scroll my-4 text-left text-sm text-primary">

          <div
            class="flex items-center justify-between py-2 border-b border-primary/5">
            <div>
              <span
                class="text-accent text-[10px] font-bold uppercase tracking-wider bg-accent/5 px-2.5 py-1 rounded-md border border-accent/10">
                {{ $selectedProduct->category->name }}
              </span>
              <h3
                class="text-lg font-bold text-primary mt-1.5">
                {{ $selectedProduct->name }}
              </h3>
            </div>
          </div>

          <div
            class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <!-- Images Gallery -->
            <div class="space-y-3">
              <p
                class="text-xs font-semibold text-accent uppercase tracking-wider">
                Gambar Produk
              </p>
              <div class="grid grid-cols-2 gap-3">
                @forelse($selectedProduct->getMedia('images') as $media)
                  <a href="{{ $media->getUrl() }}"
                    target="_blank"
                    class="block aspect-square rounded-xl border border-primary/10 overflow-hidden bg-primary/[0.02] hover:opacity-90 transition duration-150">
                    <img src="{{ $media->getUrl() }}"
                      class="w-full h-full object-cover" />
                  </a>
                @empty
                  <div
                    class="col-span-2 aspect-square rounded-xl border border-dashed border-primary/20 bg-primary/[0.01] flex flex-col items-center justify-center text-primary/40">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      class="h-8 w-8 mb-2" fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-medium">Gambar
                      Default</span>
                  </div>
                @endforelse
              </div>
            </div>

            <!-- Basic details -->
            <div
              class="space-y-4 bg-primary/[0.02] border border-primary/5 rounded-xl p-4">
              <p
                class="text-xs font-semibold text-accent uppercase tracking-wider pb-1 border-b border-primary/5">
                Detail Listing
              </p>
              <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                <div>
                  <span
                    class="text-xs text-primary/60 block">Harga</span>
                  <span
                    class="font-bold text-base text-primary">Rp
                    {{ number_format($selectedProduct->price, 0, ',', '.') }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Stok</span>
                  <span
                    class="font-bold text-base text-primary">{{ $selectedProduct->stockLabel() }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Penjual</span>
                  <span
                    class="font-medium text-primary">{{ optional($selectedProduct->seller)->name }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Email
                    Penjual</span>
                  <span
                    class="text-xs text-primary/80 truncate block">{{ optional($selectedProduct->seller)->email }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">No.
                    WhatsApp</span>
                  <span
                    class="font-medium text-primary">{{ optional($selectedProduct->seller)->whatsapp ?: '-' }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Diajukan
                    Pada</span>
                  <span
                    class="text-xs text-primary/80 block">{{ $selectedProduct->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</span>
                </div>
              </div>

              @if (count($selectedProduct->tags) > 0)
                <div
                  class="mt-4 border-t border-primary/5 pt-3">
                  <span
                    class="text-xs text-primary/60 block mb-1.5">Tags
                    Produk</span>
                  <div class="flex flex-wrap gap-1.5">
                    @foreach ($selectedProduct->tags as $tag)
                      <span
                        class="px-2 py-0.5 rounded bg-primary text-cream text-[10px]">#{{ $tag->name }}</span>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>
          </div>

          <!-- Description -->
          <div class="space-y-2">
            <p
              class="text-xs font-semibold text-accent uppercase tracking-wider">
              Deskripsi Produk
            </p>
            <div
              class="bg-primary/[0.02] border border-primary/5 rounded-xl p-4 leading-relaxed">
              <p class="font-medium text-primary text-sm">
                {{ $selectedProduct->short_description }}
              </p>
              <div class="h-px bg-primary/5 my-2.5"></div>
              <p
                class="text-primary/80 text-xs whitespace-pre-line">
                {{ $selectedProduct->description }}
              </p>
            </div>
          </div>

          <!-- Category Specific details (Polymorphic spec) -->
          <div class="space-y-2">
            <p
              class="text-xs font-semibold text-accent uppercase tracking-wider">
              Spesifikasi Tambahan (Polymorphic)
            </p>
            <div
              class="bg-primary/[0.02] border border-primary/5 rounded-xl p-4 grid grid-cols-2 sm:grid-cols-3 gap-4">
              @if ($selectedProduct->isPlant())
                <div>
                  <span
                    class="text-xs text-primary/60 block">Spesies
                    / Scientific Name</span>
                  <span
                    class="font-medium text-primary mt-0.5 block italic">{{ optional($selectedProduct->productable->species)->scientific_name }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Nama
                    Umum / Common Name</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ optional($selectedProduct->productable->species)->common_name ?: '-' }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Tingkat
                    Perawatan</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->care_level }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Pencahayaan</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->light }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Penyiraman</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->watering }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Ukuran
                    Pot</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->pot_size ?: '-' }}</span>
                </div>
              @elseif($selectedProduct->isMedia())
                <div>
                  <span
                    class="text-xs text-primary/60 block">Tipe
                    Media Tanam</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->type }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Berat
                    Bersih</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->weight ?: '-' }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Volume</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->volume ?: '-' }}</span>
                </div>
              @elseif($selectedProduct->isPot())
                <div>
                  <span
                    class="text-xs text-primary/60 block">Bahan
                    Pot</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->material }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Bentuk
                    Pot</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->shape }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Dimensi
                    Pot</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->dimensions }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Warna
                    Pot</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->color }}</span>
                </div>
              @elseif($selectedProduct->isFertilizer())
                <div>
                  <span
                    class="text-xs text-primary/60 block">Tipe
                    Pupuk</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->type }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Formulasi</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->form }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Berat
                    / Kemasan</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->weight }}</span>
                </div>
              @elseif($selectedProduct->isTool())
                <div>
                  <span
                    class="text-xs text-primary/60 block">Bahan
                    Alat</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->material }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Merek</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->brand }}</span>
                </div>
                <div>
                  <span
                    class="text-xs text-primary/60 block">Berat
                    Alat</span>
                  <span
                    class="font-medium text-primary mt-0.5 block">{{ $selectedProduct->productable->weight ?: '-' }}</span>
                </div>
              @endif
            </div>
          </div>

          <!-- Status Info (Approved / Rejected previously) -->
          @if ($selectedProduct->status === 'rejected')
            <div
              class="bg-red-50 border border-red-200/50 p-4 rounded-xl space-y-1">
              <h4
                class="text-xs font-bold text-red-800 uppercase tracking-wider">
                Alasan Penolakan Sebelumnya:
              </h4>
              <p class="text-sm text-red-700 italic">
                "{{ $selectedProduct->rejection_reason }}"
              </p>
            </div>
          @elseif($selectedProduct->status === 'approved')
            <div
              class="bg-green-50 border border-green-200/50 p-4 rounded-xl flex items-center justify-between">
              <div>
                <span
                  class="text-xs text-green-600 block uppercase font-bold tracking-wider">Info
                  Disetujui</span>
                <p
                  class="text-green-900 font-medium text-sm">
                  Disetujui oleh:
                  {{ optional($selectedProduct->approvedBy)->name ?? 'System' }}
                </p>
              </div>
              <div class="text-right">
                <span
                  class="text-xs text-primary/55 block">Tanggal
                  Approval</span>
                <span
                  class="text-xs text-green-950 font-bold block">
                  {{ $selectedProduct->approved_at ? $selectedProduct->approved_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}
                </span>
              </div>
            </div>
          @endif

          <!-- Rejection Reason input section -->
          @if ($isRejecting)
            <div
              class="bg-red-50 border border-red-200 p-4 rounded-xl space-y-3">
              <div>
                <label for="rejectionReason"
                  class="block text-sm font-semibold text-red-800 mb-1">
                  Mengapa Produk Ini Ditolak? <span
                    class="text-red-500">*</span>
                </label>
                <textarea wire:model.defer="rejectionReason"
                  id="rejectionReason" rows="3"
                  placeholder="Tulis alasan penolakan secara jelas agar dipahami penjual..."
                  class="w-full px-3 py-2 rounded-lg border border-red-300 text-sm text-primary focus:border-red-500 outline-none resize-none bg-white"></textarea>
                @error('rejectionReason')
                  <p
                    class="mt-1 text-xs text-red-600 font-medium">
                    {{ $message }}</p>
                @enderror
              </div>
              <div class="flex gap-2 justify-end">
                <button type="button"
                  wire:click="cancelReject"
                  class="px-3 py-1.5 bg-white border border-red-300 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-50 cursor-pointer transition-colors">
                  Batal
                </button>
                <button type="button" wire:click="reject"
                  class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 cursor-pointer gap-1 inline-flex items-center"
                  wire:loading.attr="disabled">
                  <x-icons.spinner wire:loading
                    wire:target="reject"
                    class="h-3.5 w-3.5 text-current" />
                  Kirim Penolakan
                </button>
              </div>
            </div>
          @endif

        </div>

        <!-- Modal Footer (Actions) -->
        <div
          class="flex gap-3 pt-4 border-t border-primary/5">
          <x-forms.cancel-button
            wire:click="$set('showDetailModal', false)">
            Tutup
          </x-forms.cancel-button>

          @if ($selectedProduct->status === 'pending' && !$isRejecting)
            <button type="button"
              wire:click="startReject"
              class="flex-1 px-4 py-2 border border-red-600 text-red-600 font-semibold text-sm rounded-xl hover:bg-red-50 cursor-pointer transition-colors gap-1 inline-flex items-center justify-center"
              wire:loading.attr="disabled">
              <x-icons.spinner wire:loading
                wire:target="startReject"
                class="h-3.5 w-3.5 text-current" />
              Tolak Listing
            </button>
            <button type="button" wire:click="approve"
              class="flex-1 px-4 py-2 bg-primary text-white font-semibold text-sm rounded-xl hover:shadow-lg transition-smooth cursor-pointer gap-1 inline-flex items-center justify-center"
              wire:loading.attr="disabled">
              <x-icons.spinner wire:loading
                wire:target="approve"
                class="h-3.5 w-3.5 text-current" />
              Setujui & Tayangkan
            </button>
          @endif
        </div>
      @endif

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

</div>
