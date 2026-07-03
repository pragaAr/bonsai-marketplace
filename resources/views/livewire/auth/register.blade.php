<div
  class="flex min-h-screen items-center justify-center p-4">
  <div class="w-full max-w-md p-8">
    <h1
      class="text-2xl font-bold text-primary text-center mb-6">
      bonsaiku</h1>

    <div class="glass rounded p-8 shadow-xl">
      <form wire:submit="register" novalidate>
        @csrf
        <div class="mb-4">
          <label for="name"
            class="block text-sm font-medium text-primary mb-1">Nama</label>
          <input x-data x-init="$nextTick(() => $el.focus())"
            wire:model.defer="name" type="text"
            name="name" id="name"
            class="w-full border border-primary/15 rounded px-3 py-2 focus:outline-none focus:border-primary/40"
            placeholder="Nama anda" autocomplete="name"
            autofocus required />
          @error('name')
            <p class="text-xs text-red-600 mt-1">
              {{ $message }}
            </p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="email"
            class="block text-sm font-medium text-primary mb-1">Email</label>
          <input wire:model.defer="email" type="email"
            name="email" id="email"
            class="w-full border border-primary/15 rounded px-3 py-2 focus:outline-none focus:border-primary/40"
            placeholder="Email anda" autocomplete="email"
            required />
          @error('email')
            <p class="text-xs text-red-600 mt-1">
              {{ $message }}
            </p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="whatsapp"
            class="block text-sm font-medium text-primary mb-1">WhatsApp</label>
          <input wire:model.defer="whatsapp" type="text"
            name="whatsapp" id="whatsapp"
            class="w-full border border-primary/15 rounded px-3 py-2 focus:outline-none focus:border-primary/40"
            placeholder="Nomor WhatsApp anda"
            autocomplete="tel" required />
          @error('whatsapp')
            <p class="text-xs text-red-600 mt-1">
              {{ $message }}
            </p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="address"
            class="block text-sm font-medium text-primary mb-1">Address</label>
          <input wire:model.defer="address" type="text"
            name="address" id="address"
            class="w-full border border-primary/15 rounded px-3 py-2 focus:outline-none focus:border-primary/40"
            placeholder="Alamat anda" autocomplete="address"
            required />
          @error('address')
            <p class="text-xs text-red-600 mt-1">
              {{ $message }}
            </p>
          @enderror
        </div>
        <div x-data="{ showPassword: false }" class="mb-4">
          <label for="password"
            class="block text-sm font-medium text-primary mb-1">Password</label>
          <div>
            <div class="relative">
              <input
                :type="showPassword ? 'text' : 'password'"
                wire:model.defer="password" name="password"
                id="password"
                class="w-full border border-primary/15 rounded px-3 py-2 pr-10 focus:outline-none focus:border-primary/40"
                placeholder="Password anda" required />
              <button type="button"
                @click="showPassword = !showPassword"
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
              <p class="text-xs text-red-600 mt-1">
                {{ $message }}
              </p>
            @enderror
          </div>
        </div>
        <div x-data="{ showPassword: false }" class="mb-4">
          <label for="password_confirmation"
            class="block text-sm font-medium text-primary mb-1">Confirm
            Password</label>
          <div>
            <div class="relative">
              <input
                :type="showPassword ? 'text' : 'password'"
                wire:model.defer="password_confirmation"
                name="password_confirmation"
                id="password_confirmation"
                class="w-full border border-primary/15 rounded px-3 py-2 pr-10 focus:outline-none focus:border-primary/40"
                placeholder="Konfirmasi Password anda"
                required />
              <button type="button"
                @click="showPassword = !showPassword"
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
              <p class="text-xs text-red-600 mt-1">
                {{ $message }}
              </p>
            @enderror
          </div>
        </div>
        <button type="submit"
          class="w-full bg-primary text-cream text-sm py-2 rounded hover:bg-primary/90 transition-colors flex w-full cursor-pointer items-center justify-center gap-2"
          wire:loading.attr="disabled"
          wire:loading.class="opacity-70"
          wire:target="register">

          <span wire:loading.remove wire:target="register">
            Daftar
          </span>
          <span wire:loading.flex wire:target="register"
            class="items-center gap-2">
            <div
              class="spinner h-4 w-4 border-2 border-white/30 border-t-white">
            </div>
            Memproses...
          </span>
        </button>
      </form>
      <p class="mt-4 text-center text-sm text-primary">
        Sudah punya akun?
        <a href="{{ route('login') }}" wire:navigate
          class="text-primary font-medium hover:underline">
          Masuk
        </a>
      </p>
    </div>
  </div>
</div>
