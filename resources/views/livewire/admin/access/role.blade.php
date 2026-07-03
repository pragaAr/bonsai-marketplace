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

      <x-page.create-button />
    </div>

    <div class="w-full mb-6">
      <x-forms.search-input placeholder="Cari role..." />
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
                Nama Role</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Permissions</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                User Count</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-primary/10">
                Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($roles as $q)
              <tr class="border-b border-primary/10"
                wire:key="{{ $q->id }}">
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->name }}
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  @if ($q->name === 'admin')
                    <span
                      class="inline-flex items-center rounded-full bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20"
                      title="{{ $q->permissions->pluck('label')->filter()->join(', ') }}">
                      All access
                    </span>
                  @else
                    @php
                      $permissionLabels = $q->permissions->map(
                          fn(
                              $permission,
                          ) => $permission->label ?:
                          $permission->name,
                      );
                    @endphp
                    <div
                      class="flex flex-wrap justify-center gap-1.5"
                      title="{{ $permissionLabels->join(', ') }}">
                      @forelse($permissionLabels->take(5) as $permissionLabel)
                        <span
                          class="inline-flex items-center rounded-full bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20">
                          {{ $permissionLabel }}
                        </span>
                      @empty
                        <span>-</span>
                      @endforelse
                      @if ($permissionLabels->count() > 5)
                        <span
                          class="inline-flex items-center rounded-full bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20">
                          ...
                        </span>
                      @endif
                    </div>
                  @endif
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->users_count }}
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
                <td colspan="4"
                  class="px-5 py-8 text-center text-primary/50">
                  Belum ada role</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4">
        {{ $roles->links('partials.custom-paginator') }}
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
      class="bg-white rounded-2xl p-6 w-full max-w-3xl flex flex-col">

      <x-modal.header wire:click="$set('showModal', false)">
        {{ $isEditing ? 'Edit' : 'Tambah' }} Role
      </x-modal.header>

      <form wire:submit="save" class="space-y-3" novalidate>
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="name">
            Nama Role
          </label>
          <input wire:model.defer="name" type="text"
            name="name" id="name"
            placeholder="Masukkan nama role"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            required>
          @error('name')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <div
            class="flex items-center justify-between mb-2">
            <label
              class="block text-sm font-medium text-primary">
              Permissions
            </label>
            <span class="text-xs text-primary/60">
              {{ count($selectedPermissions) }} dipilih
            </span>
          </div>
          <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 overflow-y-auto rounded-xl border border-primary/10 p-3 max-h-80">
            @forelse($allPermissions as $permission)
              <label
                class="flex items-center gap-3 rounded-xl border border-transparent px-3 py-2 hover:bg-primary/5 hover:border-primary/10 cursor-pointer transition-colors">

                <input wire:model="selectedPermissions"
                  type="checkbox"
                  value="{{ $permission->name }}"
                  class="rounded border-primary/20 text-primary focus:ring-primary shrink-0">

                <span
                  class="text-sm text-primary break-words leading-snug">
                  {{ $permission->name }}
                </span>

              </label>
            @empty
              <p
                class="text-sm text-primary/60 col-span-full">
                Belum ada permission.
              </p>
            @endforelse
          </div>
          @error('selectedPermissions.*')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}
            </p>
          @enderror
        </div>

        <div class="flex gap-3 pt-4">
          <x-forms.cancel-button
            wire:click="$set('showModal', false)">
            Batal
          </x-forms.cancel-button>
          <x-forms.submit-button target="save">
            Simpan
          </x-forms.submit-button>
        </div>
      </form>
    </div>
  </div>

  <x-delete-confirmation-modal :show="'showDeleteModal'"
    action="delete" title="Konfirmasi Hapus Role"
    message="Yakin ingin menghapus role ini?"
    text="Tindakan ini tidak dapat dibatalkan."
    confirmText="Ya, Hapus" />

</div>
