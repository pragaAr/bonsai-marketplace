@props([
    'show' => 'showDeleteModal',
    'action' => 'delete',
    'title' => 'Konfirmasi Hapus',
    'message' => 'Yakin ingin menghapus data ini?',
    'text' => 'Tindakan ini tidak dapat dibatalkan.',
    'confirmText' => 'Ya, Hapus',
])

<div x-data="{ show: @entangle($show) }" x-show="show"
  style="display: none;"
  class="fixed inset-0 z-50 overflow-y-auto"
  aria-labelledby="modal-title" role="dialog"
  aria-modal="true"
  x-effect="show ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')">

  <!-- Backdrop -->
  <div x-show="show"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/50 transition-opacity">
  </div>

  <div
    class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">

    <!-- Modal Panel -->
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
          class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-50">
          <svg class="h-6 w-6 text-red-600"
            fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <h3
          class="mb-2 font-heading text-lg font-bold text-primary">
          {{ $title }}
        </h3>
        <p class="mb-2 text-sm text-primary/70">
          {{ $message }}
        </p>
        <p class="mb-6 text-sm text-primary/60">
          {{ $text }}
        </p>
      </div>
      <div class="flex items-center gap-3">
        <button type="button" @click="show = false"
          class="hover:bg-primary/5 text-primary cursor-pointer transition-smooth w-full rounded-xl bg-white px-4 py-2.5 text-sm font-semibold border border-primary/10">
          Batal
        </button>
        <button type="button"
          wire:click="{{ $action }}"
          wire:loading.attr="disabled"
          class="bg-red-600 hover:bg-red-700 cursor-pointer transition-smooth inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white disabled:opacity-50">
          <svg wire:loading
            wire:target="{{ $action }}"
            class="h-4 w-4 animate-spin text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12"
              cy="12" r="10" stroke="currentColor"
              stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
          </svg>
          <span wire:loading.remove
            wire:target="{{ $action }}">
            {{ $confirmText }}
          </span>
          <span wire:loading
            wire:target="{{ $action }}">
            Menghapus…
          </span>
        </button>
      </div>
    </div>
  </div>
</div>
