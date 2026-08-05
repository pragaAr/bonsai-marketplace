<div
  class="text-center py-20 bg-white rounded-xl border border-primary/5 shadow-sm">
  <svg class="w-16 h-16 text-primary/10 mx-auto mb-4"
    fill="none" stroke="currentColor"
    stroke-width="1.5" viewBox="0 0 24 24">
    <path stroke-linecap="round"
      stroke-linejoin="round"
      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
  </svg>
  <h3 class="text-base font-semibold text-primary">
    Produk tidak ditemukan</h3>
  <p class="text-xs text-primary/50 mt-1 max-w-xs mx-auto">
    Kami tidak dapat menemukan apa yang anda cari.
    <br>
    Ubah keyword yang anda masukkan.
  </p>
  <button type="button"
    wire:click="$set('category', 'all'); $set('search', ''); $set('sort', 'default')"
    class="mt-4 inline-flex text-xs text-accent hover:underline">
    Reset Filters
  </button>
</div>
