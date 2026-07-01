<!DOCTYPE html>
<html lang="en" class="scroll-behavior-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0" />
  <title>
    {{ $title ?? 'Dashboard - bonsaiku' }}
  </title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Toastify -->
  <link rel="stylesheet"
    href="{{ asset('css/toastify.css') }}" />
  <script src="{{ asset('js/toastify.js') }}"></script>
  @livewireStyles
</head>

<body class="bg-cream" x-data="{ sidebarOpen: window.matchMedia('(min-width: 768px)').matches, userMenuOpen: false }"
  x-init="const sidebarBreakpoint = window.matchMedia('(min-width: 768px)');
  sidebarBreakpoint.addEventListener('change', event => sidebarOpen = event.matches);">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside
      :class="sidebarOpen ? 'translate-x-0 md:flex' :
          '-translate-x-full md:hidden'"
      class="fixed inset-y-0 left-0 z-40 flex bg-primary text-cream w-64 flex-shrink-0 flex-col min-h-0 transition-transform duration-300 ease-out md:static md:translate-x-0">
      <div
        class="flex h-14 flex-shrink-0 items-center justify-between px-4 border-b border-cream/20">
        <h1 class="text-xl font-bold">bonsaiku</h1>
        <button @click="sidebarOpen = false"
          class="md:hidden text-cream hover:text-cream/80">

          <svg xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd" />
          </svg>
        </button>
      </div>

      @include('partials.sidebar')
    </aside>

    <div x-show="sidebarOpen"
      x-transition:enter="transition-opacity ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition-opacity ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-30 bg-primary/50 backdrop-blur-sm md:hidden"
      style="display: none;">
    </div>

    <!-- Toastify bridge: listen for the 'toast' window event and call Toastify() -->
    <script>
      (function() {
        window.showToast = function(detail) {
          var opts = typeof detail === 'string' ? {
            message: detail
          } : detail;

          var toastType = opts.type || 'success';
          var bgColor =
            '#4a8552';
          var shadowColor =
            '0 4px 16px rgba(78, 129, 85, 0.25)';

          if (toastType === 'error') {
            bgColor = '#b91c1c';
            shadowColor =
              '0 4px 16px rgba(185, 28, 28, 0.25)';
          }

          var node = document.createElement('div');
          node.style.cssText =
            'display:flex;align-items:center;gap:10px;width:100%;';

          var msg = document.createElement('span');
          msg.style.cssText =
            'flex:1;font-size:14px;line-height:1.4;';
          msg.textContent = opts.message || '';
          node.appendChild(msg);

          Toastify({
            node: node,
            duration: opts.duration ?? 3000,
            gravity: 'bottom',
            position: 'right',
            stopOnFocus: true,
            close: true,
            style: {
              background: bgColor,
              boxShadow: shadowColor,
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

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-y-auto">
      <!-- Header with collapse button -->
      <header
        class="flex h-14 flex-shrink-0 items-center justify-between bg-cream/95 backdrop-blur-sm px-4 border-b border-primary/10">
        <div class="flex items-center gap-3">
          <button @click="sidebarOpen = !sidebarOpen"
            class="inline-flex p-2 rounded hover:bg-primary/5">
            <svg xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-primary"
              viewBox="0 0 24 24" fill="currentColor"
              stroke="currentColor" stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <line x1="4" x2="20"
                y1="12" y2="12" />
              <line x1="4" x2="20"
                y1="6" y2="6" />
              <line x1="4" x2="20"
                y1="18" y2="18" />
            </svg>
          </button>
          <h1 x-show="!sidebarOpen"
            class="text-lg font-bold text-primary"
            style="display: none;">
            bonsaiku
          </h1>
        </div>
        <div class="flex items-center space-x-4">
          <!-- User Menu Dropdown -->
          <div class="relative">
            <button @click="userMenuOpen = !userMenuOpen"
              class="flex items-center space-x-2 p-2 rounded hover:bg-primary/5">
              @auth
                @if (auth()->user()->avatar)
                  <img src="{{ auth()->user()->avatar }}"
                    alt="{{ auth()->user()->name }}"
                    class="w-5 h-5 rounded-full object-cover" />
                @else
                  <svg class="w-5 h-5 text-primary"
                    viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                      d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                      clip-rule="evenodd" />
                  </svg>
                @endif
              @endauth
            </button>

            <!-- Dropdown Menu -->
            <div x-show="userMenuOpen"
              @click.outside="userMenuOpen = false"
              class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-primary/10 z-50"
              style="display: none;">
              @auth
                <div
                  class="px-4 py-3 border-b border-primary/10">
                  <p class="text-sm font-medium text-primary">
                    {{ auth()->user()->name }}
                  </p>
                  <p class="text-xs text-primary/60">
                    {{ auth()->user()->email }}
                  </p>
                </div>
              @endauth

              <a href="{{ route('profile') }}" wire:navigate
                class="flex items-center gap-2 px-4 py-2 text-sm text-primary hover:bg-primary/5">

                <svg class="w-4 h-4" viewBox="0 0 20 20"
                  fill="currentColor">
                  <path
                    d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path fill-rule="evenodd"
                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                    clip-rule="evenodd" />
                </svg>
                <span>Profile</span>
              </a>

              <form id="logout-form" method="POST"
                action="{{ route('logout') }}"
                class="hidden">
                @csrf
              </form>
              <button type="submit" form="logout-form"
                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 border-t border-primary/10">
                <svg class="w-4 h-4 text-red-600"
                  viewBox="0 0 20 20" fill="currentColor"
                  aria-hidden="true">
                  <path fill-rule="evenodd"
                    d="M3 4.5A2.5 2.5 0 015.5 2h5A2.5 2.5 0 0113 4.5v1a1 1 0 11-2 0v-1a.5.5 0 00-.5-.5h-5a.5.5 0 00-.5.5v11a.5.5 0 00.5.5h5a.5.5 0 00.5-.5v-1a1 1 0 112 0v1a2.5 2.5 0 01-2.5 2.5h-5A2.5 2.5 0 013 15.5v-11zm10.293 3.293a1 1 0 011.414 0l2 2a1 1 0 010 1.414l-2 2a1 1 0 01-1.414-1.414L13.586 11H8a1 1 0 110-2h5.586l-.293-.293a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
                </svg>
                <span>Logout</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      <main class="p-6">
        @yield('content')
        {{ $slot ?? '' }}
      </main>

    </div>
  </div>
  @livewireScripts
</body>

</html>
