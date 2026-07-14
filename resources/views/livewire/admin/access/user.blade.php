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

      <div class="flex gap-3">
        @if ($hasActiveFilter)
          <x-page.reset-button />
        @endif

        <x-page.filter-button />
        <x-page.create-button />
      </div>

    </div>

    <div class="w-full mb-6">
      <x-forms.search-input placeholder="Cari user..." />
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
                Nama User</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Email</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Whatsapp</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Alamat</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Role</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-r border-primary/10">
                Direct Permissions</th>
              <th
                class="px-5 py-3 text-xs font-medium text-primary/70 uppercase border-b border-primary/10">
                Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $q)
              <tr class="border-b border-primary/10"
                wire:key="{{ $q->id }}">
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->name }}
                </td>
                <td
                  class="px-5 py-3 {{ $q->google_id ? 'text-green-500' : 'text-primary' }} border-r border-primary/10">
                  {{ $q->email }}
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->whatsapp ?? '-' }}
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->address ?? '-' }}
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  @forelse($q->roles as $role)
                    <span
                      class="inline-flex items-center rounded-full bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-400 inset-ring inset-ring-blue-400/30">{{ $role->name }}</span>
                  @empty
                    <span>-</span>
                  @endforelse
                </td>
                <td
                  class="px-5 py-3 text-primary border-r border-primary/10">
                  {{ $q->permissions->count() }}
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
                    wire:click="openAccess({{ $q->id }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1 text-xs text-blue-500 hover:text-blue-700 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                    title="Kelola akses">
                    <x-icons.spinner wire:loading
                      wire:target="openAccess({{ $q->id }})"
                      class="h-3.5 w-3.5 text-current" />

                    <span wire:loading.remove
                      wire:target="openAccess({{ $q->id }})">
                      <x-icons.lock class="h-4 w-4" />
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
                <td colspan="7"
                  class="px-5 py-8 text-center text-primary/50">
                  Belum ada user</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4">
        {{ $users->links('partials.custom-paginator') }}
      </div>
    </div>
  </div>

  <!-- Manage Access Modal -->
  <div x-data="{ show: @entangle('showManageModal') }" x-show="show"
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
      class="bg-white rounded-2xl p-6 w-full max-w-3xl flex flex-col max-h-[85vh]">

      <x-modal.header
        wire:click="$set('showManageModal', false)">
        Kelola Akses User
      </x-modal.header>

      <p class="text-xs text-primary/60 mt-1 mb-4">
        {{ $selectedUser?->name }}
        {{ $selectedUser ? '(' . $selectedUser->email . ')' : '' }}
      </p>

      <form wire:submit="saveAccess"
        class="space-y-3 overflow-y-auto pr-2 flex-1 sidebar-scroll"
        novalidate>
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1">
            Role
          </label>
          <div x-data="tomSelect({ value: @entangle('selectedRole'), placeholder: 'Pilih Role', ref: 'selectManageRole' })" wire:ignore
            class="w-full rounded-xl">
            <select x-ref="selectManageRole"
              x-on:change="$wire.set('selectedRole', $event.target.value)"
              class="w-full" required>
              <option value="" disabled>
                Pilih Role
              </option>
              @foreach ($allRoles as $role)
                <option value="{{ $role->name }}">
                  {{ $role->name }}
                </option>
              @endforeach
            </select>
          </div>
          @error('selectedRole')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <div
            class="flex items-center justify-between mb-2">
            <label
              class="block text-sm font-medium text-primary">
              Direct Permissions
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
            wire:click="$set('showManageModal', false)">
            Batal
          </x-forms.cancel-button>
          <x-forms.submit-button target="saveAccess">
            Simpan
          </x-forms.submit-button>
        </div>
      </form>
    </div>
  </div>

  <!-- Create User Modal -->
  <div x-data="{ show: @entangle('showCreateModal') }" x-show="show"
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
      class="bg-white rounded-2xl p-6 w-full max-w-3xl flex flex-col max-h-[85vh]">

      <x-modal.header
        wire:click="$set('showCreateModal', false)">
        {{ $isEditing ? 'Edit' : 'Tambah' }} User
      </x-modal.header>

      <form wire:submit="save"
        class="space-y-3 overflow-y-auto pr-2 flex-1 sidebar-scroll"
        novalidate>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label
              class="block text-sm font-medium text-primary mb-1"
              for="createRole">
              Role
            </label>
            <div x-data="tomSelect({ value: @entangle('createRole'), show: @entangle('showCreateModal'), placeholder: 'Pilih Role', ref: 'selectCreateRoleModal' })" wire:ignore
              class="w-full rounded-xl">
              <select x-ref="selectCreateRoleModal"
                id="createRole" class="w-full" required>
                <option value="" disabled>Pilih Role
                </option>
                @foreach ($allRoles as $role)
                  <option value="{{ $role->name }}">
                    {{ $role->name }}</option>
                @endforeach
              </select>
            </div>
            @error('createRole')
              <p class="mt-1 text-xs text-red-600">
                {{ $message }}</p>
            @enderror
          </div>

          <div>
            <label
              class="block text-sm font-medium text-primary mb-1"
              for="name">
              Nama User
            </label>
            <input wire:model.defer="name" type="text"
              name="name" id="name"
              placeholder="Masukkan nama user"
              class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
              required>
            @error('name')
              <p class="mt-1 text-xs text-red-600">
                {{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label
              class="block text-sm font-medium text-primary mb-1"
              for="email">
              Email
            </label>
            <input wire:model.defer="email" type="text"
              name="email" id="email"
              placeholder="Masukkan email"
              class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
              required>
            @error('email')
              <p class="mt-1 text-xs text-red-600">
                {{ $message }}</p>
            @enderror
          </div>

          <div>
            <label
              class="block text-sm font-medium text-primary mb-1"
              for="whatsapp">
              Whatsapp aktif
            </label>
            <input wire:model.defer="whatsapp"
              type="text" name="whatsapp"
              id="whatsapp"
              placeholder="Masukkan nomor whatsapp aktif"
              class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
              required>
            @error('whatsapp')
              <p class="mt-1 text-xs text-red-600">
                {{ $message }}</p>
            @enderror
          </div>
        </div>

        <div>
          <label
            class="block text-sm font-medium text-primary mb-1"
            for="address">
            Alamat
          </label>
          <input wire:model.defer="address" type="text"
            name="address" id="address"
            placeholder="Masukkan alamat"
            class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none"
            required>
          @error('address')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div x-data="{ showPassword: false }">
            <label
              class="block text-sm font-medium text-primary mb-1"
              for="password">
              Password
            </label>
            <div class="relative">
              <input
                :type="showPassword ? 'text' : 'password'"
                wire:model.defer="password"
                name="password" id="password"
                placeholder="••••••••" required
                class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none">
              <button type="button"
                @click="showPassword = !showPassword"
                tabindex="-1"
                class="absolute right-0 top-1/2 -translate-y-1/2 pr-3 flex items-center text-primary">
                <span :class="{ 'hidden': showPassword }">
                  <x-icons.eye />
                </span>
                <span class="hidden"
                  :class="{ 'hidden': !showPassword }">
                  <x-icons.eye-closed />
                </span>
              </button>
            </div>
            @error('password')
              <p class="mt-1 text-xs text-red-600">
                {{ $message }}</p>
            @enderror
          </div>

          <div x-data="{ showPassword: false }">
            <label
              class="block text-sm font-medium text-primary mb-1"
              for="password_confirmation">
              Confirm Password
            </label>
            <div class="relative">
              <input
                :type="showPassword ? 'text' : 'password'"
                wire:model.defer="password_confirmation"
                name="password_confirmation"
                id="password_confirmation"
                placeholder="••••••••" required
                class="w-full px-4 py-2.5 rounded-xl border border-primary/20 text-sm text-primary focus:border-primary/40 outline-none">
              <button type="button"
                @click="showPassword = !showPassword"
                tabindex="-1"
                class="absolute right-0 top-1/2 -translate-y-1/2 pr-3 flex items-center text-primary">
                <span :class="{ 'hidden': showPassword }">
                  <x-icons.eye />
                </span>
                <span class="hidden"
                  :class="{ 'hidden': !showPassword }">
                  <x-icons.eye-closed />
                </span>
              </button>
            </div>
            @error('password_confirmation')
              <p class="mt-1 text-xs text-red-600">
                {{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="flex gap-3 pt-4">
          <x-forms.cancel-button
            wire:click="$set('showCreateModal', false)">
            Batal
          </x-forms.cancel-button>
          <x-forms.submit-button target="save">
            Simpan
          </x-forms.submit-button>
        </div>
      </form>
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
      class="bg-white rounded-2xl p-6 w-full max-w-lg flex flex-col max-h-[85vh]">

      <x-modal.header
        wire:click="$set('showFilterModal', false)">
        Filter User
      </x-modal.header>

      <form wire:submit="filterList"
        x-data="{ pendingFilterRole: '{{ $filterRole }}' }"
        x-on:submit="$wire.set('filterRole', pendingFilterRole)"
        x-on:filter-reset.window="pendingFilterRole = ''"
        class="space-y-3 overflow-y-auto pr-2 flex-1 sidebar-scroll"
        novalidate>
        <div>
          <label
            class="block text-sm font-medium text-primary mb-1">
            Role
          </label>
          <div x-data="tomSelect({ value: '{{ $filterRole }}', placeholder: 'Pilih Role', ref: 'selectFilterRole' })" wire:ignore
            class="w-full"
            x-on:change.stop="pendingFilterRole = $event.target.value"
            x-on:filter-reset.window="value = ''; tomselect && tomselect.clear(true)">
            <select x-ref="selectFilterRole"
              class="w-full">
              <option value="" disabled>
                Pilih Role
              </option>
              @foreach ($allRoles as $role)
                <option value="{{ $role->name }}">
                  {{ $role->name }}
                </option>
              @endforeach
            </select>
          </div>
          @error('filterRole')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label
            class="block text-sm font-medium text-primary mb-1">
            Google User
          </label>
          <div class="flex items-center gap-3">
            <label
              class="inline-flex items-center gap-2 cursor-pointer">
              <input wire:model.defer="isGoogleUser"
                type="radio" name="isGoogleUser"
                value="1"
                class="rounded border-primary/20 text-primary focus:ring-primary shrink-0">
              <span class="text-sm text-primary">Ya</span>
            </label>
            <label
              class="inline-flex items-center gap-2 cursor-pointer">
              <input wire:model.defer="isGoogleUser"
                type="radio" name="isGoogleUser"
                value="0"
                class="rounded border-primary/20 text-primary focus:ring-primary shrink-0">
              <span
                class="text-sm text-primary">Tidak</span>
            </label>
          </div>
          @error('isGoogleUser')
            <p class="mt-1 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div class="flex gap-3 pt-4">
          <x-forms.cancel-button
            wire:click="$set('showFilterModal', false)">
            Batal
          </x-forms.cancel-button>
          <x-forms.submit-button target="filterList">
            Filter
          </x-forms.submit-button>
        </div>
      </form>
    </div>
  </div>

  <x-page.delete-modal :show="'showDeleteModal'" action="delete"
    title="Konfirmasi Hapus Role"
    message="Yakin ingin menghapus role ini?"
    text="Tindakan ini tidak dapat dibatalkan."
    confirmText="Ya, Hapus" />

</div>
