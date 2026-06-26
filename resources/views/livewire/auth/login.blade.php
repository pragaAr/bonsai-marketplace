<div class="flex min-h-screen items-center justify-center p-4">
  <div class="w-full max-w-md p-8">
    <h1 class="text-2xl font-bold text-primary text-center mb-6">bonsaiku</h1>

    <div class="glass rounded p-8 shadow-xl">
      <!-- Flash Messages -->
      @if (session('success'))
        <div class="bg-success-50 mb-6 flex items-center gap-2 rounded-xl border border-green-200 p-4 text-sm text-green-800">
          <svg 
            class="h-4 w-4 shrink-0" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24">
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          {{ session('success') }}
        </div>
      @endif
      @if (session('error'))
        <div class="bg-danger-50 mb-6 flex items-center gap-2 rounded-xl border border-red-200 p-4 text-sm text-red-800">
          <svg 
            class="h-4 w-4 shrink-0" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24">
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2"
              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          {{ session('error') }}
        </div>
      @endif

      <form wire:submit="login" class="space-y-5" novalidate>
        @csrf
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-primary mb-1">Email</label>
          <input 
            wire:model.defer="email" 
            type="email" 
            name="email" 
            id="email"   
            class="w-full border border-primary/15 rounded px-3 py-2 focus:outline-none focus:border-primary/40" placeholder="Masukan email anda" 
            autocomplete="email"
            required 
            autofocus/>
          @error('email')
            <small class="text-xs text-red-600 mt-1">
              {{ $message }}
            </small>
          @enderror
        </div>
          
        <div 
          x-data="{ showPassword: false }" 
          class="mb-4">
          <label for="password" class="block text-sm font-medium text-primary mb-1">Password</label>
          <div>
            <div class="relative">
              <input 
                :type="showPassword ? 'text' : 'password'" 
                wire:model.defer="password" 
                name="password" 
                id="password"  
                class="w-full border border-primary/15 rounded px-3 py-2 pr-10 focus:outline-none focus:border-primary/40" placeholder="••••••••" 
                autocomplete="current-password"
                required />
              <button 
                type="button" 
                @click="showPassword = !showPassword" 
                class="text-secondary-400 hover:text-secondary-600 absolute inset-y-0 right-0 flex cursor-pointer items-center justify-center px-4 focus:outline-none">
                <svg 
                  x-show="!showPassword" 
                  class="h-5 w-5" 
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke-width="1" 
                  stroke="currentColor">
                  <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                  <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg 
                  x-show="showPassword" 
                  style="display: none;" 
                  class="h-5 w-5" 
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke-width="1" 
                  stroke="currentColor">
                  <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round"
                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
              </button>
            </div>
            @error('password')
              <small class="mt-1 block text-xs text-red-600">
                {{ $message }}
              </small>
            @enderror
          </div>         
        </div>
        <div class="flex items-center justify-between mb-4">
          <label class="inline-flex items-center">
            <input 
              wire:model.defer="remember" 
              type="checkbox" 
              name="remember" 
              class="form-checkbox text-primary" />
            <span class="ml-2 text-sm text-primary">Ingat saya</span>
          </label>
          <a href="#" class="text-sm text-primary hover:underline">Lupa password?</a>
        </div>
        <button 
          type="submit" 
          class="w-full bg-primary text-cream text-sm py-2 rounded hover:bg-primary/90 transition-colors flex w-full cursor-pointer items-center justify-center gap-2"
          wire:loading.attr="disabled" 
          wire:loading.class="opacity-70">
            <span wire:loading.remove>Masuk</span>
            <span 
              wire:loading.flex 
              class="items-center gap-2">
              <div class="spinner h-4 w-4 border-2 border-white/30 border-t-white"></div>
              Memproses...
            </span>
        </button>
      </form>

      <!-- Divider -->
      <div class="relative my-5">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-primary/10"></div>
        </div>
        <div class="relative flex justify-center text-xs">
          <span class="bg-white/60 px-3 text-primary/50 font-medium uppercase tracking-wider">atau</span>
        </div>
      </div>

      <!-- Google Login Button -->
      <a
        id="btn-google-login"
        href="{{ route('auth.google') }}"
        class="group flex w-full items-center justify-center gap-3 rounded border border-primary/15 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:border-primary/30 hover:shadow-md hover:-translate-y-0.5 active:translate-y-0">
        <svg 
          class="h-5 w-5 shrink-0" 
          viewBox="0 0 24 24" 
          xmlns="http://www.w3.org/2000/svg">
          <path 
            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path 
            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path 
            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
          <path 
            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span>Masuk dengan Google</span>
      </a>

      <p class="mt-5 text-center text-sm text-primary">
        Belum punya akun? 
        <a 
          href="{{ route('register') }}"
          wire:navigate 
          class="text-primary font-medium hover:underline">
          Daftar
        </a>
      </p>
    </div>
    
  </div>
</div>
