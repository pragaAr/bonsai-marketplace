<div x-data x-init="$watch('$wire.showEditor', val => document.body.classList.toggle('overflow-hidden', val))">
  <div x-show="$wire.showEditor"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="$wire.closeEditor()"
    class="fixed inset-0 bg-black/30 z-[55]"
    style="display: none;"></div>

  <aside x-show="$wire.showEditor"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform translate-x-full"
    x-transition:enter-end="transform translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="transform translate-x-0"
    x-transition:leave-end="transform translate-x-full"
    class="fixed inset-y-0 right-0 w-full max-w-md bg-cream z-[56] flex flex-col shadow-2xl"
    aria-label="Edit profil" style="display: none;">
    <div
      class="flex items-center justify-between h-16 px-4 sm:px-6 border-b border-primary/10 shrink-0">
      <h2 class="text-lg font-semibold text-primary">Ubah
        Profil</h2>
      <button @click="$wire.closeEditor()"
        aria-label="Tutup form edit profil"
        class="p-2 -me-2 rounded-full hover:bg-primary/5 min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer transition-colors">
        <svg class="w-5 h-5 text-primary" fill="none"
          stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24">
          <path stroke-linecap="round"
            d="M18 6L6 18M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="flex-1 overflow-y-auto">
      <form wire:submit="saveProfile" class="p-6 sm:p-8">

        @if ($isGoogleOnly)
          <div
            class="mb-6 rounded-2xl border border-primary/10 bg-white px-4 py-3 text-xs leading-5 text-primary/70">
            Akun Anda terhubung dengan Google. Email tidak
            dapat diubah secara langsung di sini.
          </div>
        @endif

        <div class="space-y-5">
          @if (!$isGoogleOnly)
            <div>
              <label for="name"
                class="mb-2 block text-sm font-medium text-primary">Nama</label>
              <input id="name" type="text"
                wire:model.defer="name"
                class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" />
              @error('name')
                <p class="mt-1.5 text-xs text-red-600">
                  {{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="email"
                class="mb-2 block text-sm font-medium text-primary">Email</label>
              <input id="email" type="email"
                wire:model.defer="email"
                class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" />
              @error('email')
                <p class="mt-1.5 text-xs text-red-600">
                  {{ $message }}</p>
              @enderror
            </div>
          @endif

          <div>
            <label for="whatsapp"
              class="mb-2 block text-sm font-medium text-primary">WhatsApp</label>
            <input id="whatsapp" type="text"
              wire:model.defer="whatsapp"
              placeholder="Contoh: 08123456789"
              class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" />
            @error('whatsapp')
              <p class="mt-1.5 text-xs text-red-600">
                {{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="address"
              class="mb-2 block text-sm font-medium text-primary">Alamat</label>
            <textarea id="address" rows="3"
              wire:model.defer="address"
              placeholder="Masukkan alamat lengkap pengiriman"
              class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35 resize-none">{{ $address }}</textarea>
            @error('address')
              <p class="mt-1.5 text-xs text-red-600">
                {{ $message }}</p>
            @enderror
          </div>

          @if (!$isGoogleOnly)
            <div class="border-t border-primary/10 pt-5">
              <label for="avatarFile"
                class="mb-2 block text-sm font-medium text-primary">Foto
                Profil</label>
              <input id="avatarFile" type="file"
                wire:model="avatarFile"
                accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors file:mr-4 file:rounded-full file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-cream" />
              <p
                class="mt-2 text-xs leading-5 text-primary/50">
                File JPG, JPEG, atau PNG, maks. 2MB
                (Opsional).</p>
              @error('avatarFile')
                <p class="mt-1.5 text-xs text-red-600">
                  {{ $message }}</p>
              @enderror
            </div>

            <div class="border-t border-primary/10 pt-5">
              <p
                class="mb-4 text-sm font-medium text-primary">
                Password Baru <span
                  class="text-primary/45 font-normal">(Opsional)</span>
              </p>
              <div class="grid gap-4 sm:grid-cols-2">
                <div>
                  <label for="password"
                    class="mb-2 block text-xs text-primary/60">Password
                    baru</label>
                  <input id="password" type="password"
                    wire:model.defer="password"
                    placeholder="Min. 8 karakter"
                    class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" />
                  @error('password')
                    <p class="mt-1.5 text-xs text-red-600">
                      {{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label for="password_confirmation"
                    class="mb-2 block text-xs text-primary/60">Konfirmasi</label>
                  <input id="password_confirmation"
                    type="password"
                    wire:model.defer="password_confirmation"
                    placeholder="Ulangi password"
                    class="w-full rounded-2xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" />
                </div>
              </div>
            </div>
          @endif
        </div>

        <div
          class="mt-8 flex flex-wrap gap-3 border-t border-primary/10 pt-6">
          <button type="submit"
            class="inline-flex items-center gap-2 justify-center rounded-full bg-primary px-6 py-3 text-sm font-semibold text-cream transition-colors hover:bg-primary/90 cursor-pointer"
            wire:loading.attr="disabled">
            <span wire:loading.remove
              wire:target="saveProfile">
              Simpan perubahan
            </span>
            <span wire:loading wire:target="saveProfile">
              <span
                class="inline-flex items-center justify-center gap-2">
                <svg class="h-4 w-4 animate-spin"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25"
                    cx="12" cy="12" r="10"
                    stroke="currentColor"
                    stroke-width="4">
                  </circle>
                  <path class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
                <span>Menyimpan...</span>
              </span>
            </span>
          </button>
          <button type="button"
            @click="$wire.closeEditor()"
            class="inline-flex items-center justify-center rounded-full border border-primary/15 bg-white px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-primary/5 cursor-pointer">
            Batal
          </button>
        </div>
      </form>
    </div>
  </aside>

  <div class="pt-24 pb-16 sm:pt-28 sm:pb-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <div class="mb-8 max-w-2xl">
        <p
          class="text-xs font-semibold uppercase tracking-[0.3em] text-accent">
          Akun</p>
        <h1
          class="mt-3 text-3xl font-semibold tracking-tight text-primary sm:text-4xl">
          Profil Saya</h1>
        <p
          class="mt-4 text-sm leading-6 text-primary/70 sm:text-base">
          Ringkasan identitas akun anda.
        </p>
      </div>

      <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
        <section
          class="rounded-3xl border border-primary/10 bg-white/85 p-6 shadow-sm backdrop-blur sm:p-8">

          @if (blank($whatsapp) || blank($address))
            <div
              class="mb-6 rounded-2xl border border-accent/20 bg-accent/5 p-4 text-sm text-primary flex items-start gap-3">
              <svg
                class="h-5 w-5 text-accent shrink-0 mt-0.5"
                fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
              </svg>
              <div>
                <p class="font-semibold text-accent">
                  Lengkapi data profil Anda</p>
                <p
                  class="mt-1 text-primary/75 leading-relaxed">
                  Silakan lengkapi data WhatsApp dan alamat
                  Anda agar dapat melakukan transaksi
                  pembelian atau mengajukan permohonan
                  menjadi penjual.</p>
              </div>
            </div>
          @endif

          <div
            class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-start gap-4 sm:gap-5">
              <div
                class="h-20 w-20 overflow-hidden rounded-3xl border border-primary/10 bg-primary/5 shrink-0">
                @if ($avatar)
                  <img src="{{ $avatar }}"
                    alt="{{ $name }}"
                    class="h-full w-full object-cover" />
                @else
                  <div
                    class="flex h-full w-full items-center justify-center text-primary/50">
                    <svg class="h-9 w-9" fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="1.5">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                  </div>
                @endif
              </div>

              <div>
                <p
                  class="text-xs font-medium uppercase tracking-[0.25em] text-primary/45">
                  Identitas</p>
                <h2
                  class="mt-2 text-2xl font-semibold tracking-tight text-primary">
                  {{ $name }}</h2>
                <p class="mt-2 text-sm text-primary/65">
                  {{ $email }}</p>
              </div>
            </div>
          </div>

          <div class="mt-8 grid gap-3 sm:grid-cols-2">
            <div
              class="rounded-2xl border border-primary/10 bg-primary/5 p-4">
              <p
                class="text-xs uppercase tracking-[0.2em] text-primary/45">
                WhatsApp</p>
              <p
                class="mt-2 text-sm font-semibold text-primary">
                {{ $whatsapp ?? 'Belum diisi' }}</p>
            </div>
            <div
              class="rounded-2xl border border-primary/10 bg-primary/5 p-4">
              <p
                class="text-xs uppercase tracking-[0.2em] text-primary/45">
                Alamat</p>
              <p
                class="mt-2 text-sm font-semibold text-primary break-all">
                {{ $address ?? 'Belum diisi' }}</p>
            </div>
          </div>

          <div class="mt-8 flex flex-wrap gap-3">
            <button type="button" wire:click="openEditor"
              wire:loading.attr="disabled"
              wire:target="openEditor"
              class="inline-flex items-center gap-2 justify-center rounded-full border border-primary/15 bg-white px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-primary/5 cursor-pointer">

              <svg wire:loading wire:target="openEditor"
                class="h-4 w-4 animate-spin"
                xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12"
                  cy="12" r="10"
                  stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
              Ubah profil
            </button>
            @if ($hasGoogleAvatar && !$hasCustomAvatar)
              <button type="button"
                wire:click="useGoogleAvatar"
                class="inline-flex items-center justify-center rounded-full border border-primary/15 bg-white px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-primary/5 cursor-pointer">
                Sync Foto profile Google
              </button>
            @endif
          </div>
        </section>

        <section class="space-y-6">
          <div
            class="rounded-3xl border border-primary/10 bg-primary text-cream p-6 shadow-sm sm:p-8">
            <div
              class="flex items-start justify-between gap-4">
              <div>
                <p
                  class="text-xs font-medium uppercase tracking-[0.25em] text-cream/60">
                  Penjual</p>
                <h2 class="mt-2 text-xl font-semibold">
                  {{ $sellerLabel }}</h2>

                @if (!$isSeller)
                  <p
                    class="mt-2 text-sm leading-6 text-cream/75">
                    Ingin menjual produk anda? Ajukan
                    permohonan untuk menjadi penjual.
                  </p>
                @endif
              </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
              @if ($isSeller)
                <a href="{{ route('seller.dashboard') }}"
                  wire:navigate
                  class="inline-flex items-center justify-center rounded-full bg-cream px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-cream/90">
                  Seller Dashboard
                </a>
              @else
                <a href="{{ route('seller.apply') }}"
                  wire:navigate
                  class="inline-flex items-center justify-center rounded-full bg-cream px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-cream/90">
                  Jadi Penjual
                </a>
              @endif
            </div>
          </div>

          <div
            class="rounded-3xl border border-primary/10 bg-white/85 p-6 shadow-sm sm:p-8">
            <p
              class="text-xs font-medium uppercase tracking-[0.25em] text-primary/45">
              Pembelian</p>
            <h2
              class="mt-2 text-xl font-semibold text-primary">
              History pembelian</h2>
            <p
              class="mt-2 text-sm leading-6 text-primary/65">
              Lihat riwayat transaksi dan status pesanan
              dari halaman khusus pembelian.
            </p>

            <div class="mt-6">
              <a href="{{ route('profile.orders') }}"
                wire:navigate
                class="inline-flex items-center justify-center rounded-full bg-primary px-5 py-3 text-sm font-semibold text-cream transition-colors hover:bg-primary/90">
                Buka history pembelian
              </a>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>
