<div>
  <div class="space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-primary">
          {{ $title }}</h1>
        <p class="text-sm text-primary/60 mt-1">
          {{ $subTitle }}</p>
      </div>
      <div class="flex gap-3">
        @if ($hasActiveFilter)
          <x-page.reset-button />
        @endif
        <x-page.filter-button />
      </div>
    </div>

    <div class="w-full mb-6">
      <x-forms.search-input
        placeholder="Cari nama toko, pemilik, atau WhatsApp..." />
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
                Nama Toko</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Pemilik</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                User</th>
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
            @forelse ($sellers as $seller)
              <tr wire:key="seller-{{ $seller->id }}"
                class="border-b border-primary/10">
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 font-medium">
                  {{ $seller->store_name }}</td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $seller->owner_name }}</td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 text-left">
                  <div class="font-medium">
                    {{ $seller->user?->name }}</div>
                  <div class="text-xs text-primary/60">
                    {{ $seller->user?->email }}</div>
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $seller->whatsapp }}</td>
                <td
                  class="px-5 py-3 border-r border-primary/10">
                  <span
                    class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $seller->status === 'approved' ? 'bg-green-400/10 text-green-600' : 'bg-gray-400/10 text-gray-600' }}">
                    {{ $seller->status === 'approved' ? 'Disetujui' : 'Banned' }}
                  </span>
                </td>
                <td class="px-5 py-3 whitespace-nowrap">
                  @if ($seller->status === 'approved')
                    <button
                      wire:click="confirmBan({{ $seller->id }})"
                      wire:loading.attr="disabled"
                      class="inline-flex items-center gap-1 text-xs text-orange-600 hover:text-orange-800 font-medium cursor-pointer">
                      <x-icons.lock class="h-4 w-4" />
                      Bekukan
                    </button>
                  @else
                    <button
                      wire:click="unban({{ $seller->id }})"
                      wire:loading.attr="disabled"
                      class="inline-flex items-center gap-1 text-xs text-primary hover:text-accent font-medium cursor-pointer">
                      Aktifkan kembali
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6"
                  class="px-6 py-8 text-center text-primary/50">
                  Belum ada seller dengan status ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4">
        {{ $sellers->links('partials.custom-paginator') }}
      </div>
    </div>

  </div>

  <div x-data="{ show: @entangle('showFilterModal') }" x-show="show"
    x-transition.opacity.duration.300ms
    style="display: none;"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    x-effect="show ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')">
    <div x-show="show" x-trap="show" x-transition
      class="bg-white rounded-2xl p-6 w-full max-w-md">
      <x-modal.header
        wire:click="$set('showFilterModal', false)">Filter
        Seller</x-modal.header>
      <form wire:submit="filterList" x-data="{ pendingFilterStatus: '{{ $filterStatus }}' }"
        x-on:submit="$wire.set('filterStatus', pendingFilterStatus)"
        x-on:filter-reset.window="pendingFilterStatus = 'approved'"
        class="space-y-4 my-4">
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1">Status
            Seller</label>
          <div x-data="tomSelect({ lazy: true, show: @entangle('showFilterModal'), value: '{{ $filterStatus }}', placeholder: 'Status Seller', ref: 'selectFilterStatus' })" wire:ignore
            x-on:change.stop="pendingFilterStatus = $event.target.value"
            x-on:filter-reset.window="pendingFilterStatus = 'approved'; value = 'approved'; tomselect && tomselect.setValue('approved', true)">
            <select x-ref="selectFilterStatus"
              class="w-full">
              <option value="approved">Disetujui
                (Approved)</option>
              <option value="banned">Dibekukan (Banned)
              </option>
            </select>
          </div>
        </div>
        <div class="flex gap-3 pt-4">
          <x-forms.cancel-button
            wire:click="$set('showFilterModal', false)">Batal</x-forms.cancel-button>
          <button type="submit" wire:target="filterList"
            wire:loading.attr="disabled"
            class="flex-1 px-4 py-2 bg-primary text-white font-semibold text-sm rounded-xl cursor-pointer">
            <x-icons.spinner wire:loading
              wire:target="filterList"
              class="h-3.5 w-3.5 text-current" />
            Terapkan Filter
          </button>
        </div>
      </form>
    </div>
  </div>

  <div x-data="{ show: @entangle('showBanModal') }" x-show="show"
    style="display: none;"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div x-show="show" x-trap="show" x-transition
      class="bg-white rounded-2xl p-6 w-full max-w-sm text-center">
      <h3 class="text-lg font-bold text-primary">Bekukan
        Seller?</h3>
      <p class="text-sm text-primary/70 mt-2 mb-6">Hak
        akses seller akan dicabut sampai diaktifkan
        kembali.</p>
      <div class="flex gap-3">
        <button type="button" @click="show = false"
          class="w-full rounded-xl border border-primary/10 px-4 py-2 text-sm font-semibold cursor-pointer">Batal</button>
        <button type="button" wire:click="ban"
          wire:loading.attr="disabled"
          class="w-full rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white cursor-pointer">Ya,
          Bekukan</button>
      </div>
    </div>
  </div>

</div>
