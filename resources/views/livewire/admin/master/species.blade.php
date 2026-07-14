<div>
  <div class="space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-primary">
          {{ $title ?: 'Spesies Tanaman' }}
        </h1>
        <p class="text-sm text-primary/60 mt-1">
          {{ $subTitle ?: 'Kelola spesies tanaman bonsai.' }}
        </p>
      </div>

      <x-page.create-button wire:click="openCreate" />
    </div>

    <div class="w-full mb-6">
      <x-forms.search-input
        placeholder="Cari nama ilmiah atau slug spesies..." />
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
                Nama Ilmiah</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Nama Umum</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Slug</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-primary/10">
                Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($species as $q)
              <tr class="border-b border-primary/10"
                wire:key="{{ $q->id }}">
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 text-left font-medium">
                  {{ $q->scientific_name }}</td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->common_name ?? '-' }}</td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->slug }}</td>
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
                      wire:target="openEdit({{ $q->id }})"><x-icons.edit /></span>
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
                      wire:target="confirmDelete({{ $q->id }})"><x-icons.delete /></span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4"
                  class="px-5 py-8 text-center text-primary/50">
                  Belum ada spesies.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4">
        {{ $species->links('partials.custom-paginator') }}
      </div>
    </div>
  </div>

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
      <x-modal.header wire:click="$set('showModal', false)">
        {{ $isEditing ? 'Edit' : 'Tambah' }} Spesies
      </x-modal.header>

      <form wire:submit.prevent="save" class="space-y-4"
        novalidate>
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="scientific_name">Nama Ilmiah</label>
          <input wire:model.defer="scientific_name"
            type="text" name="scientific_name"
            id="scientific_name"
            placeholder="Contoh: Ficus benjamina"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            required>
          @error('scientific_name')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="common_name">Nama Umum</label>
          <input wire:model.defer="common_name"
            type="text" name="common_name"
            id="common_name"
            placeholder="Contoh: Beringin Lokal"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            required>
          @error('common_name')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="slug">Slug</label>
          <input wire:model.defer="slug" type="text"
            name="slug" id="slug"
            placeholder="Contoh: ficus-benjamina"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            required>
          @error('slug')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div class="flex gap-3 pt-4">
          <x-forms.cancel-button
            wire:click="$set('showModal', false)">Batal</x-forms.cancel-button>
          <x-forms.submit-button
            target="save">Simpan</x-forms.submit-button>
        </div>
      </form>
    </div>
  </div>

  <x-page.delete-modal :show="'showDeleteModal'" action="delete"
    title="Konfirmasi Hapus Spesies"
    message="Yakin ingin menghapus spesies ini?"
    text="Tindakan ini tidak dapat dibatalkan."
    confirmText="Ya, Hapus" />
</div>
