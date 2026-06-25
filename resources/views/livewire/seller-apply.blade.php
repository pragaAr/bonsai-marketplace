<div class="pt-24 pb-16 sm:pt-28 sm:pb-20">
  <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-primary/10 bg-white/85 p-6 shadow-sm backdrop-blur sm:p-8">
      <p class="text-xs font-semibold uppercase tracking-[0.3em] text-accent">Seller</p>
      <h1 class="mt-3 text-3xl font-semibold tracking-tight text-primary sm:text-4xl">Pengajuan Menjadi Seller</h1>
      <p class="mt-4 max-w-2xl text-sm leading-6 text-primary/70 sm:text-base">
        Isi profil singkat toko agar pengajuan bisa diteruskan untuk pemeriksaan admin.
      </p>

      <form wire:submit="submit" class="mt-8 space-y-5">
        <div>
          <label for="store_name" class="mb-2 block text-sm font-medium text-primary">Nama Toko</label>
          <input id="store_name" type="text" wire:model.defer="store_name" class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" placeholder="Contoh: Bonsai Senja" />
          @error('store_name')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="whatsapp" class="mb-2 block text-sm font-medium text-primary">WhatsApp</label>
          <input id="whatsapp" type="text" wire:model.defer="whatsapp" class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" placeholder="08xxxxxxxxxx" />
          @error('whatsapp')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="notes" class="mb-2 block text-sm font-medium text-primary">Catatan singkat</label>
          <textarea id="notes" wire:model.defer="notes" rows="5" class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" placeholder="Ceritakan sedikit tentang koleksi bonsai, pengalaman, dan rencana toko."></textarea>
          @error('notes')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex flex-wrap gap-3 pt-2">
          <button type="submit" class="inline-flex items-center justify-center rounded-full bg-primary px-5 py-3 text-sm font-semibold text-cream transition-colors hover:bg-primary/90" wire:loading.attr="disabled">
            Kirim pengajuan
          </button>
          <a href="{{ route('profile') }}" wire:navigate class="inline-flex items-center justify-center rounded-full border border-primary/15 bg-white px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-primary/5">
            Kembali ke profil
          </a>
        </div>
      </form>
    </div>
  </div>
</div>