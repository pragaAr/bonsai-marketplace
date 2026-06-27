<div class="pt-24 pb-16 sm:pt-28 sm:pb-20">
  <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
    <div
      class="rounded-3xl border border-primary/10 bg-white/85 p-6 shadow-sm backdrop-blur sm:p-8">
      <p
        class="text-xs font-semibold uppercase tracking-[0.3em] text-accent">
        Penjual</p>
      <h1
        class="mt-3 text-3xl font-semibold tracking-tight text-primary sm:text-4xl">
        Pengajuan Menjadi Penjual</h1>
      <p
        class="mt-4 max-w-2xl text-sm leading-6 text-primary/70 sm:text-base">
        Isi profil toko agar pengajuan bisa
        diteruskan untuk pemeriksaan admin.
      </p>

      <form wire:submit="submit" class="mt-8 space-y-5">
        <div>
          <label for="store_name"
            class="mb-2 block text-sm font-medium text-primary">Nama
            Toko</label>
          <input id="store_name" type="text"
            wire:model.defer="store_name"
            class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35"
            placeholder="Contoh: Bonsai Senja" />
          @error('store_name')
            <p class="mt-1.5 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="owner_name"
            class="mb-2 block text-sm font-medium text-primary">Nama
            Pemilik</label>
          <input id="owner_name" type="text"
            wire:model.defer="owner_name"
            class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35"
            placeholder="Contoh: Budi Sujana" />
          @error('owner_name')
            <p class="mt-1.5 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="province_name"
            class="mb-2 block text-sm font-medium text-primary">Provinsi</label>
          <input id="province_name" type="text"
            wire:model.defer="province_name"
            class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35"
            placeholder="Contoh: Jawa Tengah" />
          @error('province_name')
            <p class="mt-1.5 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="city_name"
            class="mb-2 block text-sm font-medium text-primary">Kota</label>
          <input id="city_name" type="text"
            wire:model.defer="city_name"
            class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35"
            placeholder="Contoh: Semarang" />
          @error('city_name')
            <p class="mt-1.5 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="whatsapp"
            class="mb-2 block text-sm font-medium text-primary">WhatsApp</label>
          <input id="whatsapp" type="text"
            wire:model.defer="whatsapp"
            class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35"
            placeholder="08xxxxxxxxxx" />
          @error('whatsapp')
            <p class="mt-1.5 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="notes"
            class="mb-2 block text-sm font-medium text-primary">Catatan
            singkat</label>
          <textarea id="notes" wire:model.defer="notes"
            rows="5"
            class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35"
            placeholder="Ceritakan sedikit tentang koleksi bonsai, pengalaman, dan rencana toko."></textarea>
          @error('notes')
            <p class="mt-1.5 text-xs text-red-600">
              {{ $message }}</p>
          @enderror
        </div>

        <div class="flex flex-wrap gap-3 pt-2">
          <button type="submit"
            class="inline-flex items-center gap-2 justify-center rounded-full bg-primary px-5 py-3 text-sm font-semibold text-cream transition-colors hover:bg-primary/90 cursor-pointer"
            wire:loading.attr="disabled">
            <svg wire:loading wire:target="submit"
              class="h-4 w-4 animate-spin"
              xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12"
                cy="12" r="10" stroke="currentColor"
                stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
            Kirim pengajuan
          </button>
          <a href="{{ route('profile') }}" wire:navigate
            class="inline-flex items-center justify-center rounded-full border border-primary/15 bg-white px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-primary/5">
            Kembali ke profil
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
