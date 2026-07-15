<div>
  <div class="space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-primary">
          {{ $title ?: 'Tags' }}
        </h1>
        <p class="text-sm text-primary/60 mt-1">
          {{ $subTitle ?: 'Kelola tag produk' }}
        </p>
      </div>

      <x-page.create-button wire:click="openCreate" />
    </div>

    <div class="w-full mb-6">
      <x-forms.search-input
        placeholder="Cari nama, slug, atau kategori tag..." />
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
                Nama</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Slug</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Kategori</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Deskripsi</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-primary/10">
                Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tags as $tag)
              <tr class="border-b border-primary/10"
                wire:key="{{ $tag->id }}">
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 text-left font-medium">
                  {{ $tag->name }}</td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $tag->slug }}</td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $tag->category?->name ?? '-' }}</td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10 text-left">
                  {{ $tag->description }}</td>
                <td
                  class="px-5 py-3 text-center whitespace-nowrap space-x-3">
                  <button
                    wire:click="openEdit({{ $tag->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-orange-600 hover:text-orange-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Edit">
                    <x-icons.spinner wire:loading
                      wire:target="openEdit({{ $tag->id }})"
                      class="h-3.5 w-3.5 text-current" />
                    <span wire:loading.remove
                      wire:target="openEdit({{ $tag->id }})"><x-icons.edit /></span>
                  </button>

                  <button
                    wire:click="confirmDelete({{ $tag->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Hapus">
                    <x-icons.spinner wire:loading
                      wire:target="confirmDelete({{ $tag->id }})"
                      class="h-3.5 w-3.5 text-current" />
                    <span wire:loading.remove
                      wire:target="confirmDelete({{ $tag->id }})"><x-icons.delete /></span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5"
                  class="px-5 py-8 text-center text-primary/50">
                  Belum ada tag.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4">
        {{ $tags->links('partials.custom-paginator') }}
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
        {{ $isEditing ? 'Edit' : 'Tambah' }} Tag
      </x-modal.header>

      <form wire:submit="save" class="space-y-4" novalidate>
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="name">Nama Tag</label>
          <input wire:model.defer="name" type="text"
            name="name" id="name"
            placeholder="Contoh: bonsai"
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
            for="slug">Slug</label>
          <input wire:model.defer="slug" type="text"
            name="slug" id="slug"
            placeholder="Contoh: bonsai"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            required>
          @error('slug')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="categoryId">Kategori
          </label>

          <div x-data="tomSelect({ lazy: true, value: @entangle('categoryId'), show: @entangle('showModal'), placeholder: 'Pilih kategori', ref: 'selectCategoryModal' })" wire:ignore class="w-full rounded-xl">
            <select x-ref="selectCategoryModal"
              id="categoryId" class="w-full" required>
              <option value="" disabled>Pilih kategori
              </option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                  {{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          @error('categoryId')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="description">Deskripsi</label>
          <textarea wire:model.defer="description" name="description"
            id="description" rows="3"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            placeholder="Deskripsi singkat tag"></textarea>
          @error('description')
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
    title="Konfirmasi Hapus Tag"
    message="Yakin ingin menghapus tag ini?"
    text="Tindakan ini tidak dapat dibatalkan."
    confirmText="Ya, Hapus" />
</div>
