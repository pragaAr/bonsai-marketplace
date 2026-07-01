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

      <svg wire:loading wire:target="openCreate"
        class="h-4 w-4 animate-spin text-white"
        xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12"
          cy="12" r="10" stroke="currentColor"
          stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
        </path>
      </svg>
      <span>Tambah</span>
    </button>
  </div>

  <div class="w-full mb-6">
    <input wire:model.live.debounce.300ms="search"
      type="text" placeholder="Cari role..."
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
          @forelse($roles as $role)
            <tr
              class="hover:bg-primary/5 transition-colors border-b border-primary/10">
              <td
                class="px-5 py-3 font-medium text-primary border-r border-primary/10">
                {{ $role->name }}
              </td>
              <td
                class="px-5 py-3 text-primary/70 border-r border-primary/10">
                @if ($role->name === 'admin')
                  <span
                    class="inline-flex items-center rounded-full bg-accent/50 px-2.5 py-1 text-xs font-semibold text-primary"
                    title="{{ $role->permissions->pluck('label')->filter()->join(', ') }}">
                    All access
                  </span>
                @else
                  @php
                    $permissionLabels = $role->permissions->map(
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
                        class="inline-flex items-center rounded-full bg-accent/50 px-2.5 py-1 text-xs font-medium text-primary">
                        {{ $permissionLabel }}
                      </span>
                    @empty
                      <span
                        class="text-secondary-400">-</span>
                    @endforelse
                    @if ($permissionLabels->count() > 5)
                      <span
                        class="inline-flex items-center rounded-full bg-accent/50 px-2.5 py-1 text-xs font-medium text-primary">...</span>
                    @endif
                  </div>
                @endif
              </td>
              <td
                class="px-5 py-3 text-primary border-r border-primary/10">
                {{ $role->users_count }}
              </td>
              <td
                class="px-5 py-3 text-center whitespace-nowrap space-x-3">
                <button
                  wire:click="openEdit({{ $role->id }})"
                  wire:loading.attr="disabled"
                  class="inline-flex items-center gap-1 text-xs text-orange-600 hover:text-orange-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                  title="Edit">
                  <x-spinner wire:loading
                    wire:target="openEdit({{ $role->id }})"
                    class="h-3.5 w-3.5 text-current" />

                  <span wire:loading.remove
                    wire:target="openEdit({{ $role->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      fill="none" viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="h-4 w-4 text-current">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                  </span>
                </button>

                <button
                  wire:click="confirmDelete({{ $role->id }})"
                  wire:loading.attr="disabled"
                  class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-medium cursor-pointer transition-opacity disabled:opacity-50"
                  title="Hapus">
                  <x-spinner wire:loading
                    wire:target="confirmDelete({{ $role->id }})"
                    class="h-3.5 w-3.5 text-current" />

                  <span wire:loading.remove
                    wire:target="confirmDelete({{ $role->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      fill="none" viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="h-4 w-4 text-current">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
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
