<div>
  <div class="space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-primary">
          {{ $title ?: '' }}
        </h1>
        <p class="text-sm text-primary/60 mt-1">
          {{ $subTitle ?: '' }}
        </p>
      </div>

      <button wire:click="openCreate"
        wire:loading.attr="disabled" wire:target="openCreate"
        class="w-full sm:w-auto px-5 py-2 bg-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg transition-smooth cursor-pointer inline-flex items-center justify-center gap-2 disabled:opacity-50 self-start sm:self-center">

        <svg wire:loading.remove wire:target="openCreate"
          xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
          viewBox="0 0 24 24" stroke-width="2"
          stroke="currentColor" fill="none"
          stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z"
            fill="none" />
          <path d="M12 5l0 14" />
          <path d="M5 12l14 0" />
        </svg>

        <x-icons.spinner wire:loading
          wire:target="openCreate"
          class="h-3.5 w-3.5 text-current" />

        <span>Tambah</span>
      </button>
    </div>

    <div class="w-full mb-6">
      <input wire:model.live.debounce.300ms="search"
        type="text" placeholder="Cari permission..."
        class="px-4 py-2 rounded-xl bg-white/50 border border-primary/20 text-sm focus:border-primary/40 outline-none w-full">
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
                Nama Permissions</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Label</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-primary/10">
                Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($permissions as $q)
              <tr class="border-b border-primary/10"
                wire:key="{{ $q->id }}">
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->name }}
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->label }}
                </td>
                <td
                  class="px-5 py-3 text-center whitespace-nowrap space-x-3">
                  <button
                    wire:click="openEdit({{ $q->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-orange-600 hover:text-orange-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Edit">
                    <x-icons.spinner wire:loading
                      wire:target="openEdit({{ $q->id }})"
                      class="h-3.5 w-3.5 text-current" />

                    <span wire:loading.remove
                      wire:target="openEdit({{ $q->id }})">
                      <x-icons.edit />
                    </span>
                  </button>

                  <button
                    wire:click="confirmDelete({{ $q->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Hapus">
                    <x-icons.spinner wire:loading
                      wire:target="confirmDelete({{ $q->id }})"
                      class="h-3.5 w-3.5 text-current" />

                    <span wire:loading.remove
                      wire:target="confirmDelete({{ $q->id }})">
                      <x-icons.delete />
                    </span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3"
                  class="px-5 py-8 text-center text-primary/50">
                  Belum ada permission</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4">

        {{ $permissions->links('partials.custom-paginator') }}
      </div>
    </div>

  </div>

  <!-- Modal -->
  <div x-data="{ show: @entangle('showModal') }" x-show="show"
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
      class="bg-white rounded-2xl p-6 w-full max-w-lg flex flex-col">
      <div class="flex items-center justify-between mb-4">
        <h3
          class="font-heading font-semibold text-lg text-primary">
          {{ $isEditing ? 'Edit' : 'Tambah' }} Permission
        </h3>
        <button type="button"
          wire:click="$set('showModal', false)"
          class="text-primary/60 hover:text-primary/80 cursor-pointer rounded-lg p-1 text-xl transition-colors">
          &times;
        </button>
      </div>

      <form wire:submit="save" class="space-y-3" novalidate>
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="name">
            Nama Permission
          </label>
          <input wire:model.defer="name" type="text"
            name="name" id="name"
            placeholder="contoh: roles.manage"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            required>
          @error('name')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="label">
            Label Permission
          </label>
          <input wire:model.defer="label" type="text"
            name="label" id="label"
            placeholder="contoh: Lihat Role"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            required>
          @error('label')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div class="flex gap-3 pt-1">
          <button type="button"
            wire:click="$set('showModal', false)"
            class="flex-1 px-4 py-2.5 border border-primary/10 rounded-xl text-sm font-medium text-primary hover:bg-primary/5 cursor-pointer">
            Batal
          </button>
          <button type="submit"
            wire:loading.attr="disabled"
            class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 cursor-pointer disabled:opacity-50 inline-flex items-center justify-center gap-2">
            <x-icons.spinner wire:loading
              wire:target="save"
              class="h-3.5 w-3.5 text-current" />
            <span>Simpan</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <x-delete-confirmation-modal :show="'showDeleteModal'"
    action="delete" title="Konfirmasi Hapus Permission"
    message="Yakin ingin menghapus permission ini?"
    text="Tindakan ini tidak dapat dibatalkan."
    confirmText="Ya, Hapus" />

</div>
