<!DOCTYPE html>
<html lang="en" class="scroll-behavior-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0" />
  <title>
    bonsaiku {{ $title ? '| ' . $title : '' }}
  </title>
  <meta name="description"
    content="Curated bonsai collection for mindful living. Premium indoor and outdoor bonsai trees, care guides, and expert advice." />

  <link rel="preconnect"
    href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com"
    crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />

  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet"
    href="{{ asset('css/styles.css') }}" />
  <!-- Toastify -->
  <link rel="stylesheet"
    href="{{ asset('css/toastify.css') }}" />
  <script src="{{ asset('js/toastify.js') }}"></script>
  @livewireStyles
</head>

<body class="bg-cream text-primary font-sans antialiased"
  x-data="{ mobileMenuOpen: false, searchOpen: false, cartOpen: false }"
  x-effect="document.body.style.overflow = (mobileMenuOpen || cartOpen) ? 'hidden' : ''"
  @cart-opened.window="cartOpen = true"
  @cart-closed.window="cartOpen = false">

  <!-- Header -->
  <header id="main-header"
    class="fixed top-0 left-0 right-0 z-50 bg-cream/95 backdrop-blur-sm transition-shadow duration-300"
    :class="window.scrollY > 10 ? 'header-scrolled' : ''">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">

        <a href="/" wire:navigate
          class="text-xl font-bold text-primary tracking-tight flex items-center">
          <img src="{{ asset('images/logo.png') }}"
            alt="Bonsaiku" class="h-8 w-8 mr-2" />
          bonsaiku
        </a>
        <nav class="hidden md:flex items-center gap-8"
          aria-label="Main navigation">
          <a href="/about" wire:navigate
            class="text-sm font-medium transition-colors hover:text-accent {{ request()->is('about*') ? 'text-accent' : 'text-primary' }}">
            Tentang
          </a>
          <a href="/shop" wire:navigate
            class="text-sm font-medium transition-colors hover:text-accent {{ request()->is('shop*') ? 'text-accent' : 'text-primary' }}">
            Koleksi
          </a>
          <a href="/care-guide" wire:navigate
            class="text-sm font-medium transition-colors hover:text-accent {{ request()->is('care-guide*') ? 'text-accent' : 'text-primary' }}">
            Panduan
          </a>
          <a href="/article" wire:navigate
            class="text-sm font-medium transition-colors hover:text-accent {{ request()->is('article*') ? 'text-accent' : 'text-primary' }}">
            Artikel
          </a>
        </nav>
        <div class="flex items-center gap-2">
          <!-- Search toggle -->
          <button @click="searchOpen = !searchOpen"
            aria-label="Cari Produk"
            class="rounded-full hover:bg-primary/5 transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer">
            <svg class="w-5 h-5 text-primary" fill="none"
              stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="8" />
              <path d="m21 21-4.35-4.35" />
            </svg>
          </button>

          <!-- Livewire Cart Indicator/Badge -->
          @livewire('cart-badge')

          <!-- User Profile Dropdown / Login Button -->
          @auth
            <div x-data="{ open: false, avatarUrl: '{{ e(auth()->user()->avatar) }}' }"
              @click.away="open = false"
              @avatar-updated.window="avatarUrl = $event.detail.avatarUrl"
              class="hidden md:block relative">
              <button @click="open = !open"
                class="flex items-center justify-center rounded-full overflow-hidden border border-primary/10 hover:border-primary/30 focus:outline-none transition-all duration-200 h-7 w-7 cursor-pointer ml-1"
                aria-haspopup="true" :aria-expanded="open">
                <template x-if="avatarUrl">
                  <img :src="avatarUrl"
                    alt="{{ auth()->user()->name }}"
                    class="h-full w-full object-cover">
                </template>
                <template x-if="!avatarUrl">
                  <div
                    class="h-full w-full bg-primary/5 flex items-center justify-center text-primary/70">
                    <svg class="h-5 w-5" fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="1.5">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                  </div>
                </template>
              </button>

              <!-- Dropdown Menu -->
              <div x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 rounded-xl bg-white border border-primary/10 shadow-lg py-1 z-50 text-left"
                style="display: none;">
                <!-- User Info Header -->
                <div
                  class="px-4 py-3 border-b border-primary/5">
                  <p
                    class="text-sm font-semibold text-primary truncate">
                    {{ auth()->user()->name }}
                  </p>
                  <p class="text-xs text-primary/60 truncate">
                    {{ auth()->user()->email }}
                  </p>
                </div>

                <!-- Navigation Links -->
                <div class="py-1">
                  @if (auth()->user()->hasAnyRole(['system_admin', 'admin']))
                    <a href="{{ route('admin.dashboard') }}"
                      wire:navigate
                      class="block px-4 py-2 text-sm text-primary hover:bg-primary/5 transition-colors cursor-pointer">
                      Dashboard Admin
                    </a>
                  @endif
                  @if (auth()->user()->hasRole('seller'))
                    <a href="{{ route('seller.dashboard') }}"
                      wire:navigate
                      class="block px-4 py-2 text-sm text-primary hover:bg-primary/5 transition-colors cursor-pointer">
                      Dashboard Seller
                    </a>
                  @endif
                  <a href="{{ route('profile') }}"
                    wire:navigate
                    class="block px-4 py-2 text-sm text-primary hover:bg-primary/5 transition-colors cursor-pointer">
                    Profil Saya
                  </a>
                </div>

                <!-- Logout -->
                <div class="border-t border-primary/5 py-1">
                  <form action="{{ route('logout') }}"
                    method="POST" id="logout-form-desktop"
                    class="hidden">
                    @csrf
                  </form>
                  <button type="button"
                    onclick="document.getElementById('logout-form-desktop').submit();"
                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors cursor-pointer font-medium">
                    Keluar
                  </button>
                </div>
              </div>
            </div>
          @else
            <a href="{{ route('login') }}" wire:navigate
              x-data="{ loading: false }" @click="loading = true"
              :class="loading ? 'opacity-85 pointer-events-none' :
                  ''"
              class="hidden md:inline-flex items-center justify-center gap-2 rounded px-4 py-1.5 text-sm font-semibold text-primary border border-primary/15 hover:bg-primary/5 transition-colors cursor-pointer">

              <!-- Spinner -->
              <x-icons.spinner x-show="loading" x-cloak
                class="h-4 w-4 text-current" />

              <span x-text="loading ? 'Memuat...' : 'Masuk'">
                Masuk
              </span>
            </a>
          @endauth

          <!-- Mobile menu toggle -->
          <button @click="mobileMenuOpen = true"
            aria-label="Open menu"
            class="md:hidden p-2.5 -me-3 rounded-full hover:bg-primary/5 transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer">
            <svg class="w-5 h-5 text-primary"
              fill="none" stroke="currentColor"
              stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round"
                d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

        </div>
      </div>
    </div>

    <!-- Search Overlay -->
    <div x-show="searchOpen"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 -translate-y-2"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-2"
      @click.away="searchOpen = false"
      class="absolute top-full left-0 right-0 bg-cream border-b border-primary/10 px-4 py-4"
      style="display: none;">
      <div class="max-w-xl mx-auto relative">
        <form action="/shop" method="GET"
          x-on:submit.prevent="Livewire.navigate('/shop?q=' + encodeURIComponent($el.querySelector('input[name=q]').value))">
          <input type="search" name="q"
            x-effect="if (searchOpen) $nextTick(() => $el.focus())"
            placeholder="Search bonsai…"
            class="w-full bg-white border border-primary/15 rounded-lg px-4 py-3 pl-11 text-sm text-primary focus:outline-none focus:border-primary/40"
            value="{{ request('q') }}" />
        </form>
        <svg
          class="w-5 h-5 text-primary/40 absolute left-3.5 top-1/2 -translate-y-1/2"
          fill="none" stroke="currentColor"
          stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.35-4.35" />
        </svg>
      </div>
    </div>
  </header>

  <!-- Mobile Menu Panel -->
  <div>
    <!-- Backdrop -->
    <div x-show="mobileMenuOpen"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-300"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      @click="mobileMenuOpen = false"
      class="fixed inset-0 bg-black/30 z-[55]"
      style="display: none;"></div>

    <!-- Navigation panel -->
    <nav x-show="mobileMenuOpen"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="transform translate-x-full"
      x-transition:enter-end="transform translate-x-0"
      x-transition:leave="transition ease-in duration-300"
      x-transition:leave-start="transform translate-x-0"
      x-transition:leave-end="transform translate-x-full"
      class="fixed inset-y-0 right-0 w-full max-w-md bg-cream z-[56] flex flex-col shadow-2xl"
      aria-label="Mobile navigation"
      style="display: none;">

      <!-- Drawer Header -->
      <div
        class="flex items-center justify-between h-16 px-4 sm:px-6 border-b border-primary/10">
        <span
          class="text-xl font-bold text-primary tracking-tight">bonsaiku</span>
        <button @click="mobileMenuOpen = false"
          aria-label="Close menu"
          class="p-2 -me-2 rounded-full hover:bg-primary/5 min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer">
          <svg class="w-5 h-5 text-primary" fill="none"
            stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round"
              d="M18 6L6 18M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Drawer Content -->
      <div class="flex-1 overflow-y-auto p-6">
        <div class="flex flex-col gap-2">
          <a href="/" wire:navigate
            class="block text-2xl font-light text-primary py-3 hover:text-accent transition-colors cursor-pointer">
            Beranda
          </a>
          <a href="/about" wire:navigate
            class="block text-2xl font-light text-primary py-3 hover:text-accent transition-colors cursor-pointer">
            Tentang
          </a>
          <a href="/shop" wire:navigate
            class="block text-2xl font-light text-primary py-3 hover:text-accent transition-colors cursor-pointer">
            Koleksi
          </a>
          <a href="/care-guide" wire:navigate
            class="block text-2xl font-light text-primary py-3 hover:text-accent transition-colors cursor-pointer">
            Panduan
          </a>
          <a href="/article" wire:navigate
            class="block text-2xl font-light text-primary py-3 hover:text-accent transition-colors cursor-pointer">
            Artikel
          </a>
          @auth
            <div class="mt-6 border-t border-primary/10 pt-6"
              x-data="{ avatarUrl: '{{ e(auth()->user()->avatar) }}' }"
              @avatar-updated.window="avatarUrl = $event.detail.avatarUrl">
              <div class="flex items-center gap-3 mb-4">
                <template x-if="avatarUrl">
                  <img :src="avatarUrl"
                    alt="{{ auth()->user()->name }}"
                    class="h-10 w-10 rounded-full object-cover">
                </template>
                <template x-if="!avatarUrl">
                  <div
                    class="h-10 w-10 rounded-full bg-primary/5 flex items-center justify-center text-primary/70 border border-primary/10">
                    <svg class="h-5 w-5" fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="1.5">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                  </div>
                </template>
                <div>
                  <p
                    class="text-sm font-semibold text-primary truncate max-w-[200px]">
                    {{ auth()->user()->name }}
                  </p>
                  <p
                    class="text-xs text-primary/60 truncate max-w-[200px]">
                    {{ auth()->user()->email }}
                  </p>
                </div>
              </div>

              <div class="space-y-1">
                @if (auth()->user()->hasAnyRole(['system_admin', 'admin']))
                  <a href="{{ route('admin.dashboard') }}"
                    class="block py-2 text-base font-medium text-primary hover:text-accent transition-colors">
                    Dashboard Admin
                  </a>
                @endif
                @if (auth()->user()->hasRole('seller'))
                  <a href="{{ route('seller.dashboard') }}"
                    class="block py-2 text-base font-medium text-primary hover:text-accent transition-colors">
                    Dashboard Seller
                  </a>
                @endif
                <a href="{{ route('profile') }}"
                  wire:navigate
                  class="block py-2 text-base font-medium text-primary hover:text-accent transition-colors">
                  Profil Saya
                </a>
              </div>

              <form action="{{ route('logout') }}"
                method="POST" id="logout-form-mobile"
                class="hidden">
                @csrf
              </form>
              <button type="button"
                onclick="document.getElementById('logout-form-mobile').submit();"
                class="mt-4 w-full inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-5 py-2.5 text-base font-semibold text-red-700 transition-colors hover:bg-red-100 cursor-pointer">
                Keluar
              </button>
            </div>
          @else
            <a href="{{ route('login') }}" wire:navigate
              class="mt-4 inline-flex items-center justify-center rounded-full bg-primary px-5 py-3 text-base font-semibold text-cream transition-colors hover:bg-primary/90">
              Masuk/Daftar
            </a>
          @endauth
        </div>
      </div>

      <!-- Drawer Footer -->
      <div class="border-t border-primary/10 p-6 bg-cream">
        <p class="text-xs text-primary/40 text-center">
          &copy; {{ date('Y') }}
          bonsaiku. Temukan, Rawat, Berkarya
        </p>
      </div>
    </nav>
  </div>

  <!-- Livewire Cart Drawer Component -->
  @livewire('cart-drawer')

  <!-- Auth guard helper for frontend buttons -->
  <script>
    window.isAuthenticated = @json(auth()->check());
    window.loginUrl = @json(route('login'));
    window.requireAuth = function(callback) {
      if (window.isAuthenticated) {
        callback();
      } else {
        window.showToast && window.showToast({
          message: 'Silakan login terlebih dahulu.',
          actionText: 'Login',
          actionUrl: window.loginUrl,
          duration: 4000,
        });
      }
    };
  </script>

  <!-- Toastify bridge: listen for the 'toast' window event and call Toastify() -->
  <script>
    (function() {
      window.showToast = function(detail) {
        var opts = typeof detail === 'string' ? {
          message: detail
        } : detail;
        var node = document.createElement('div');
        node.style.cssText =
          'display:flex;align-items:center;gap:10px;width:100%;';

        // Message
        var msg = document.createElement('span');
        msg.style.cssText =
          'flex:1;font-size:14px;line-height:1.4;';
        msg.textContent = opts.message || '';
        node.appendChild(msg);

        // Action link
        if (opts.actionUrl) {
          var link = document.createElement('a');
          link.href = opts.actionUrl;
          link.textContent = opts.actionText || 'Buka';
          link.style.cssText =
            'flex-shrink:0;border-radius:9999px;background:#F5F5F0;padding:2px 10px;font-size:12px;font-weight:600;color:#2D3E2F;text-decoration:none;transition:background .2s;';
          link.onmouseenter = function() {
            link.style.background = '#B4A67F';
          };
          link.onmouseleave = function() {
            link.style.background = '#F5F5F0';
          };
          node.appendChild(link);
        }

        Toastify({
          node: node,
          duration: opts.duration ?? 3000,
          gravity: 'top',
          position: 'right',
          stopOnFocus: true,
          close: true,
          style: {
            background: '#2D3E2F',
            borderRadius: '8px',
            boxShadow: '0 4px 16px rgba(45,62,47,0.18)',
            padding: '10px 14px',
            maxWidth: '380px',
            color: '#F5F5F0',
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
          },
          offset: {
            x: 16,
            y: 72
          },
        }).showToast();
      };

      // Guard: hanya register listener sekali untuk mencegah duplikat saat wire:navigate
      // Livewire SPA mode (wire:navigate) mengeksekusi ulang inline <script> setiap
      // navigasi, sehingga tanpa guard ini addEventListener terpanggil berkali-kali.
      if (!window.__toastListenerRegistered) {
        window.__toastListenerRegistered = true;
        window.addEventListener('toast', function(e) {
          window.showToast(e.detail);
        });
      }
    })();
  </script>

  <!-- Main View -->
  <main class="min-h-screen">
    {{ $slot }}
  </main>

  <!-- Footer -->
  <footer class="bg-primary text-cream/80">
    <div
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
        <div>
          <h3 class="text-xl font-bold text-cream mb-4">
            bonsaiku</h3>
          <p
            class="text-sm leading-relaxed text-cream/60 max-w-xs">
            Dari komunitas untuk komunitas.</p>
        </div>
        <div>
          <h4
            class="text-sm font-semibold text-cream mb-4 uppercase tracking-wider">
            Jelajahi</h4>
          <nav class="flex flex-col gap-2.5"
            aria-label="Footer navigation">
            <a href="/about" wire:navigate
              class="text-sm hover:text-cream transition-colors">
              Tentang
            </a>
            <a href="/shop" wire:navigate
              class="text-sm hover:text-cream transition-colors">
              Koleksi
            </a>
            <a href="/care-guide" wire:navigate
              class="text-sm hover:text-cream transition-colors">
              Panduan
            </a>
            <a href="/article" wire:navigate
              class="text-sm hover:text-cream transition-colors">
              Artikel
            </a>
          </nav>
        </div>
        <div>
          <h4
            class="text-sm font-semibold text-cream mb-4 uppercase tracking-wider">
            Kategori</h4>
          <div class="flex flex-col gap-3">
            <a href="/shop?category=bonsai" wire:navigate
              class="text-sm hover:text-cream transition-colors">
              Bonsai
            </a>
            <a href="/shop?category=pot-bonsai"
              wire:navigate
              class="text-sm hover:text-cream transition-colors">
              Pot Bonsai
            </a>
            <a href="/shop?category=peralatan"
              wire:navigate
              class="text-sm hover:text-cream transition-colors">
              Peralatan
            </a>
            <a href="/shop?category=aksesoris"
              wire:navigate
              class="text-sm hover:text-cream transition-colors">
              Aksesoris
            </a>
          </div>
        </div>
        <div>
          <h4
            class="text-sm font-semibold text-cream mb-4 uppercase tracking-wider">
            Hubungi Kami</h4>
          <div class="flex flex-col gap-3">
            <a href="https://wa.me/6281234567890"
              target="_blank" rel="noopener noreferrer"
              class="inline-flex items-center gap-2 text-sm hover:text-cream transition-colors">
              <svg class="w-4 h-4" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                  d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                <path
                  d="M12 0C5.373 0 0 5.373 0 12c0 2.12.553 4.11 1.519 5.838L0 24l6.335-1.652A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.82a9.78 9.78 0 01-5.283-1.545l-.379-.226-3.93 1.025 1.05-3.83-.248-.394A9.78 9.78 0 012.18 12c0-5.422 4.398-9.82 9.82-9.82s9.82 4.398 9.82 9.82-4.398 9.82-9.82 9.82z" />
              </svg>
              WhatsApp
            </a>
            <a href="#"
              class="inline-flex items-center gap-2 text-sm hover:text-cream transition-colors">
              <svg class="w-4 h-4" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                  d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
              </svg>
              Instagram
            </a>
            <a href="#"
              class="inline-flex items-center gap-2 text-sm hover:text-cream transition-colors">
              <svg class="w-4 h-4" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                  d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z" />
              </svg>
              TikTok
            </a>
            <a href="#"
              class="inline-flex items-center gap-2 text-sm hover:text-cream transition-colors">
              <svg class="w-4 h-4" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                  d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
              </svg>
              Facebook
            </a>
          </div>
        </div>
      </div>
      <div
        class="mt-12 pt-6 border-t border-cream/10 text-center">
        <p class="text-xs text-cream/40">
          &copy; {{ date('Y') }}
          bonsaiku. Temukan, Rawat, Berkarya
        </p>
      </div>
    </div>
  </footer>

  @livewireScripts
</body>

</html>
