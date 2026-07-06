<div>
  <div class="space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-primary">
          {{ $title ?: 'Permintaan Penjual' }}
        </h1>
        <p class="text-sm text-primary/60 mt-1">
          {{ $subTitle ?: 'Kelola pendaftaran dan status kemitraan penjual' }}
        </p>
      </div>

      <div class="flex gap-3">
        @if ($hasActiveFilter)
          <x-page.reset-button />
        @endif

        <x-page.filter-button />
      </div>
    </div>

    <div class="w-full mb-6">
      <x-forms.search-input placeholder="Cari nama toko, pemilik, atau WhatsApp..." />
    </div>

    <!-- Table -->
    <div
      class="bg-white rounded-xl shadow-sm border border-primary/10">
      <div class="overflow-x-auto px-6 py-4">
        <table
          class="min-w-full text-sm border border-primary/10 border-collapse text-center">
          <thead class="bg-primary/5">
            <tr>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Nama Toko</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Pemilik</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                User Pengaju</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Provinsi / Kota</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                WhatsApp</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Status</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-primary/10">
                Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($requests as $req)
              <tr class="border-b border-primary/10"
                wire:key="{{ $req->id }}">
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 font-medium">
                  {{ $req->store_name }}
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $req->owner_name }}
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  <div class="text-left font-medium">
                    {{ $req->user?->name }}</div>
                  <div class="text-left text-xs text-primary/60">
                    {{ $req->user?->email }}</div>
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 text-xs">
                  {{ $req->province_name }} / {{ $req->city_name }}
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $req->whatsapp) }}"
                    target="_blank"
                    class="text-accent hover:underline font-medium">
                    {{ $req->whatsapp }}
                  </a>
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  @php
                    $statusColors = match ($req->status) {
                        'pending' => 'bg-amber-400/10 text-amber-600 inset-ring-amber-400/30',
                        'approved' => 'bg-green-400/10 text-green-600 inset-ring-green-400/30',
                        'rejected' => 'bg-red-400/10 text-red-600 inset-ring-red-400/30',
                        'banned' => 'bg-gray-400/10 text-gray-600 inset-ring-gray-400/30',
                        default => 'bg-gray-400/10 text-gray-500',
                    };
                    $statusLabel = match ($req->status) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'banned' => 'Banned',
                        default => $req->status,
                    };
                  @endphp
                  <span
                    class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $statusColors }}">
                    {{ $statusLabel }}
                  </span>
                </td>
                <td
                  class="px-5 py-3 text-center whitespace-nowrap space-x-3">
                  <!-- Detail Button -->
                  <button wire:click="openDetail({{ $req->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-primary hover:text-accent font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Lihat Detail">
                    <x-icons.spinner wire:loading
                      wire:target="openDetail({{ $req->id }})"
                      class="h-3.5 w-3.5 text-current" />
                    <span wire:loading.remove
                      wire:target="openDetail({{ $req->id }})">
                      <x-icons.eye class="h-4 w-4" />
                    </span>
                  </button>

                  <!-- Ban Button (Only visible if status is approved) -->
                  @if ($req->status === 'approved')
                    <button wire:click="confirmBan({{ $req->id }})"
                      wire:loading.attr="disabled"
                      class="inline-flex items-center gap-1 text-xs text-orange-600 hover:text-orange-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                      title="Bekukan Seller (Ban)">
                      <x-icons.spinner wire:loading
                        wire:target="confirmBan({{ $req->id }})"
                        class="h-3.5 w-3.5 text-current" />
                      <span wire:loading.remove
                        wire:target="confirmBan({{ $req->id }})">
                        <x-icons.lock class="h-4 w-4" />
                      </span>
                    </button>
                  @endif

                  <!-- Delete Button -->
                  <button wire:click="confirmDelete({{ $req->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Hapus">
                    <x-icons.spinner wire:loading
                      wire:target="confirmDelete({{ $req->id }})"
                      class="h-3.5 w-3.5 text-current" />
                    <span wire:loading.remove
                      wire:target="confirmDelete({{ $req->id }})">
                      <x-icons.delete class="h-4 w-4" />
                    </span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7"
                  class="px-5 py-8 text-center text-primary/50">
                  Belum ada permintaan seller</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4">
        {{ $requests->links('partials.custom-paginator') }}
      </div>
    </div>
  </div>

  <!-- Detail & Approve/Reject Modal -->
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
      class="bg-white rounded-2xl p-6 w-full max-w-2xl flex flex-col max-h-[85vh]">

      <x-modal.header
        wire:click="$set('showDetailModal', false)">
        Detail Pengajuan Penjual
      </x-modal.header>

      @if ($selectedRequest)
        <div
          class="space-y-4 overflow-y-auto pr-2 flex-1 sidebar-scroll my-4 text-left">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div
              class="bg-primary/[0.02] p-4 rounded-xl border border-primary/5">
              <h3
                class="text-xs font-semibold text-accent uppercase tracking-wider mb-2">
                Informasi Akun User</h3>
              <div class="space-y-1 text-sm text-primary">
                <div><span class="text-primary/60 font-medium font-sans">Nama:</span>
                  {{ $selectedRequest->user?->name }}</div>
                <div><span class="text-primary/60 font-medium font-sans">Email:</span>
                  {{ $selectedRequest->user?->email }}</div>
                <div><span class="text-primary/60 font-medium font-sans">No. HP
                    (Akun):</span>
                  {{ $selectedRequest->user?->whatsapp ?? '-' }}</div>
                <div><span class="text-primary/60 font-medium font-sans">Alamat
                    (Akun):</span>
                  {{ $selectedRequest->user?->address ?? '-' }}</div>
              </div>
            </div>

            <div
              class="bg-primary/[0.02] p-4 rounded-xl border border-primary/5">
              <h3
                class="text-xs font-semibold text-accent uppercase tracking-wider mb-2">
                Informasi Toko Diajukan</h3>
              <div class="space-y-1 text-sm text-primary">
                <div><span class="text-primary/60 font-medium font-sans">Nama
                    Toko:</span>
                  {{ $selectedRequest->store_name }}</div>
                <div><span class="text-primary/60 font-medium font-sans">Pemilik:</span>
                  {{ $selectedRequest->owner_name }}</div>
                <div><span class="text-primary/60 font-medium font-sans">No. HP
                    Toko:</span>
                  {{ $selectedRequest->whatsapp }}</div>
                <div><span
                    class="text-primary/60 font-medium font-sans">Lokasi:</span>
                  {{ $selectedRequest->city_name }},
                  {{ $selectedRequest->province_name }}</div>
              </div>
            </div>
          </div>

          <div
            class="bg-primary/[0.02] p-4 rounded-xl border border-primary/5 space-y-2">
            <h3
              class="text-xs font-semibold text-accent uppercase tracking-wider">
              Catatan Tambahan</h3>
            <p
              class="text-sm text-primary/80 bg-white p-3 rounded-lg border border-primary/5 italic">
              "{{ $selectedRequest->notes ?: 'Tidak ada catatan.' }}"
            </p>
          </div>

          <div
            class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-primary/60">
            <div><span class="font-semibold text-primary/70">Waktu
                Pengajuan:</span>
              {{ $selectedRequest->created_at->isoFormat('D MMMM YYYY, HH:mm') }}
            </div>
            <div>
              <span class="font-semibold text-primary/70">Status Saat
                Ini:</span>
              <span
                class="font-semibold uppercase {{ $selectedRequest->status === 'approved' ? 'text-green-600' : ($selectedRequest->status === 'rejected' ? 'text-red-600' : 'text-amber-500') }}">
                {{ $selectedRequest->status }}
              </span>
            </div>
            @if ($selectedRequest->reviewed_at)
              <div><span class="font-semibold text-primary/70">Ditinjau
                  Pada:</span>
                {{ $selectedRequest->reviewed_at->isoFormat('D MMMM YYYY, HH:mm') }}
              </div>
              <div><span class="font-semibold text-primary/70">Ditinjau
                  Oleh:</span>
                {{ $selectedRequest->reviewer?->name ?? 'System' }}</div>
            @endif
          </div>

          @if ($selectedRequest->status === 'rejected' && $selectedRequest->rejection_reason)
            <div
              class="bg-red-50 border border-red-200/50 p-4 rounded-xl space-y-1">
              <h4
                class="text-xs font-bold text-red-800 uppercase tracking-wider">
                Alasan Penolakan Admin:</h4>
              <p class="text-sm text-red-700 italic">
                "{{ $selectedRequest->rejection_reason }}"</p>
            </div>
          @endif

          <!-- Rejection Reason input section -->
          @if ($isRejecting)
            <div class="bg-red-50 border border-red-200 p-4 rounded-xl space-y-3">
              <div>
                <label for="rejectionReason"
                  class="block text-sm font-semibold text-red-800 mb-1">
                  Alasan Penolakan
                </label>
                <textarea wire:model.defer="rejectionReason"
                  id="rejectionReason" rows="3"
                  placeholder="Tulis alasan mengapa pengajuan ditolak..."
                  class="w-full px-3 py-2 rounded-lg border border-red-300 text-sm text-primary focus:border-red-500 outline-none resize-none bg-white"></textarea>
                @error('rejectionReason')
                  <p class="mt-1 text-xs text-red-600">
                    {{ $message }}</p>
                @enderror
              </div>
              <div class="flex gap-2 justify-end">
                <button type="button" wire:click="cancelReject"
                  class="px-3 py-1.5 bg-white border border-red-300 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-50 cursor-pointer">
                  Batal
                </button>
                <button type="button" wire:click="reject"
                  class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 cursor-pointer">
                  Kirim & Tolak
                </button>
              </div>
            </div>
          @endif
        </div>

        <div class="flex gap-3 pt-4 border-t border-primary/10">
          <x-forms.cancel-button
            wire:click="$set('showDetailModal', false)">
            Tutup
          </x-forms.cancel-button>

          @if ($selectedRequest->status === 'pending' && !$isRejecting)
            <button type="button" wire:click="startReject"
              class="px-4 py-2 border border-red-600 text-red-600 font-semibold text-sm rounded-xl hover:bg-red-50 cursor-pointer transition-colors">
              Tolak Pengajuan
            </button>
            <button type="button" wire:click="approve"
              class="flex-1 px-4 py-2 bg-primary text-white font-semibold text-sm rounded-xl hover:shadow-lg transition-smooth cursor-pointer">
              Setujui Jadi Penjual
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

      <form wire:submit="filterList" class="space-y-4 my-4 flex-1 text-left">
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1">
            Status Pengajuan
          </label>
          <select wire:model.defer="filterStatus"
            class="w-full px-3 py-2 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none bg-white">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Ditinjau (Pending)
            </option>
            <option value="approved">Disetujui (Approved)</option>
            <option value="rejected">Ditolak (Rejected)</option>
            <option value="banned">Dibekukan (Banned)</option>
          </select>
        </div>

        <div class="flex gap-3 pt-4 border-t border-primary/10">
          <x-forms.cancel-button
            wire:click="$set('showFilterModal', false)">
            Batal
          </x-forms.cancel-button>
          <button type="submit"
            class="flex-1 px-4 py-2 bg-primary text-white font-semibold text-sm rounded-xl hover:shadow-lg transition-smooth cursor-pointer">
            Terapkan Filter
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Ban/Freeze Confirmation Modal -->
  <div x-data="{ show: @entangle('showBanModal') }" x-show="show"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true"
    x-effect="show ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')">
    <div x-show="show" x-transition:enter="ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 bg-black/50 transition-opacity"></div>
    <div
      class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
      <div x-show="show" x-trap="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-sm transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
        <div
          class="flex flex-col items-center justify-center text-center">
          <div
            class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-orange-50">
            <svg class="h-6 w-6 text-orange-600" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <h3
            class="mb-2 font-heading text-lg font-bold text-primary">
            Bekukan Akun Seller
          </h3>
          <p class="mb-2 text-sm text-primary/70">
            Yakin ingin membekukan (ban) toko seller ini?
          </p>
          <p class="mb-6 text-sm text-primary/60">
            Setelah dibekukan, hak akses sebagai seller akan dicabut dan
            seller ini tidak dapat mengelola toko mereka lagi.
          </p>
        </div>
        <div class="flex items-center gap-3">
          <button type="button" @click="show = false"
            class="hover:bg-primary/5 text-primary cursor-pointer transition-smooth w-full rounded-xl bg-white px-4 py-2.5 text-sm font-semibold border border-primary/10">
            Batal
          </button>
          <button type="button" wire:click="ban"
            wire:loading.attr="disabled"
            class="bg-orange-600 hover:bg-orange-700 cursor-pointer transition-smooth inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white disabled:opacity-50">
            <x-icons.spinner wire:loading wire:target="ban"
              class="h-4 w-4 animate-spin text-current" />
            <span wire:loading.remove wire:target="ban">Ya,
              Bekukan</span>
            <span wire:loading wire:target="ban">Memproses…</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Reusable Delete Modal -->
  <x-page.delete-modal :show="'showDeleteModal'" action="delete"
    title="Konfirmasi Hapus Pengajuan"
    message="Yakin ingin menghapus data pengajuan seller ini?"
    text="Tindakan ini tidak dapat dibatalkan."
    confirmText="Ya, Hapus" />

</div>
