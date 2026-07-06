<div class="flex flex-1 min-h-0 flex-col text-sm">
  @php
    $linkBase =
        'flex items-center gap-3 border-l-2 border-transparent px-3 py-2 rounded-r transition-colors duration-150 hover:border-accent hover:bg-accent/10 hover:text-white';
    $subLinkBase =
        'flex items-center gap-2 border-l-2 border-transparent px-2 py-2 rounded-r transition-colors duration-150 hover:border-accent hover:bg-accent/10 hover:text-white';
    $activeLink = 'border-accent bg-accent/20 text-white';
  @endphp

  <!-- Scrollable Content -->
  <div
    class="flex-1 min-h-0 overflow-y-auto sidebar-scroll p-4 space-y-3">
    @auth
      @php
        $user = auth()->user();
      @endphp

      @if (
          $user->hasRole('admin') ||
              $user->hasRole('system_admin'))
        <div class="space-y-2">
          <a href="{{ route('admin.dashboard') }}"
            wire:navigate
            class="{{ $linkBase }} {{ request()->routeIs('admin.dashboard') ? $activeLink : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path
                d="M19 21H5a2 2 0 0 1-2-2v-9a1 1 0 0 1 .4-.8l7-5.25a2 2 0 0 1 2.4 0l7 5.25a1 1 0 0 1 .4.8v9a2 2 0 0 1-2 2z" />
              <path d="M12 21v-6" />
            </svg>

            <span
              class="font-medium text-cream">Dashboard</span>
          </a>

          <div class="h-px bg-cream/20 w-full my-3"></div>

          <details class="group rounded bg-white/5" open>
            <summary
              class="flex items-center justify-between border-l-2 border-transparent px-3 py-2 rounded-r cursor-pointer transition-colors duration-150 hover:border-accent hover:bg-accent/10 hover:text-white">
              <span class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                  class="w-4 h-4" viewBox="0 0 24 24"
                  fill="none" stroke="currentColor"
                  stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round">
                  <rect x="3" y="3" width="7"
                    height="7" />
                  <rect x="14" y="3" width="7"
                    height="7" />
                  <rect x="14" y="14" width="7"
                    height="7" />
                  <rect x="3" y="14" width="7"
                    height="7" />
                </svg>
                <span class="text-cream">Master Data</span>
              </span>
              <svg
                class="w-4 h-4 text-cream transition-transform duration-150 group-open:-rotate-180"
                viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.957a.75.75 0 111.08 1.04l-4.25 4.535a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                  clip-rule="evenodd" />
              </svg>
            </summary>
            <div
              class="flex flex-col px-4 py-2 space-y-1 text-cream/90">
              <a href="#" class="{{ $subLinkBase }}">
                <x-icons.minus class="w-4 h-4" />

                <span>Kategori Produk</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <x-icons.minus class="w-4 h-4" />

                <span>Kategori Artikel</span>
              </a>
            </div>
          </details>

          <div class="h-px bg-cream/20 w-full my-3"></div>

          <details class="group rounded bg-white/5" open>
            <summary
              class="flex items-center justify-between border-l-2 border-transparent px-3 py-2 rounded-r cursor-pointer transition-colors duration-150 hover:border-accent hover:bg-accent/10 hover:text-white">
              <span class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                  class="w-4 h-4" viewBox="0 0 24 24"
                  fill="none" stroke="currentColor"
                  stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round">
                  <path
                    d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                  <circle cx="9" cy="7"
                    r="4" />

                  <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                  <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>

                <span class="text-cream">Manage Sellers</span>
              </span>
              <svg
                class="w-4 h-4 text-cream transition-transform duration-150 group-open:-rotate-180"
                viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.957a.75.75 0 111.08 1.04l-4.25 4.535a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                  clip-rule="evenodd" />
              </svg>
            </summary>
            <div
              class="flex flex-col px-4 py-2 space-y-1 text-cream/90">
              <a href="{{ route('admin.seller.request') }}"
                wire:navigate
                class="{{ $subLinkBase }} {{ request()->routeIs('admin.seller.request') ? $activeLink : '' }}">
                <x-icons.minus class="w-4 h-4" />

                <span>Pengajuan Seller</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <x-icons.minus class="w-4 h-4" />

                <span>Daftar Seller</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <x-icons.minus class="w-4 h-4" />

                <span>Payout Requests</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <x-icons.minus class="w-4 h-4" />

                <span>Seller Reviews</span>
              </a>
            </div>
          </details>

          <div class="h-px bg-cream/20 w-full my-3"></div>

          <details class="group rounded bg-white/5" open>
            <summary
              class="flex items-center justify-between border-l-2 border-transparent px-3 py-2 rounded-r cursor-pointer transition-colors duration-150 hover:border-accent hover:bg-accent/10 hover:text-white">
              <span class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                  class="w-4 h-4" viewBox="0 0 24 24"
                  fill="none" stroke="currentColor"
                  stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round">
                  <path
                    d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                  <path d="M9 11V9a3 3 0 0 1 6 0v2" />
                  <rect x="8" y="11" width="8"
                    height="5" rx="1" />
                </svg>
                <span class="text-cream">Manage Access</span>
              </span>
              <svg
                class="w-4 h-4 text-cream transition-transform duration-150 group-open:-rotate-180"
                viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.957a.75.75 0 111.08 1.04l-4.25 4.535a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                  clip-rule="evenodd" />
              </svg>
            </summary>
            <div
              class="flex flex-col px-4 py-2 space-y-1 text-cream/90">
              <a href="{{ route('admin.roles') }}"
                wire:navigate
                class="{{ $linkBase }} {{ request()->routeIs('admin.roles') ? $activeLink : '' }}">
                <x-icons.minus class="w-4 h-4" />
                <span>Roles</span>
              </a>
              <a href="{{ route('admin.permissions') }}"
                wire:navigate
                class="{{ $linkBase }} {{ request()->routeIs('admin.permissions') ? $activeLink : '' }}">
                <x-icons.minus class="w-4 h-4" />

                <span>Permissions</span>
              </a>
              <a href="{{ route('admin.users') }}"
                wire:navigate
                class="{{ $linkBase }} {{ request()->routeIs('admin.users') ? $activeLink : '' }}">
                <x-icons.minus class="w-4 h-4" />

                <span>Users</span>
              </a>
            </div>
          </details>
        </div>
      @elseif($user->hasRole('seller'))
        <div class="space-y-2">
          <a href="#" class="{{ $linkBase }}">
            <svg class="w-4 h-4 text-cream"
              viewBox="0 0 20 20" fill="currentColor"
              aria-hidden="true">
              <path d="M4 4h12v12H4V4zm2 2v8h8V6H6z" />
            </svg>
            <span class="font-medium text-cream">Seller
              Dashboard</span>
          </a>

          <div
            class="px-3 pt-1 text-[11px] uppercase tracking-[0.2em] text-cream/60">
            Toko</div>
          <details class="group rounded bg-white/5" open>
            <summary
              class="flex items-center justify-between border-l-2 border-transparent px-3 py-2 rounded-r cursor-pointer transition-colors duration-150 hover:border-accent hover:bg-accent/10 hover:text-white">
              <span class="flex items-center gap-3">
                <svg class="w-4 h-4 text-cream"
                  viewBox="0 0 20 20" fill="currentColor"
                  aria-hidden="true">
                  <path
                    d="M4 5h12v2H4V5zm0 4h12v2H4V9zm0 4h12v2H4v-2z" />
                </svg>
                <span class="text-cream">Toko Bonsai</span>
              </span>
              <svg
                class="w-4 h-4 text-cream transition-transform duration-150 group-open:-rotate-180"
                viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.957a.75.75 0 111.08 1.04l-4.25 4.535a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                  clip-rule="evenodd" />
              </svg>
            </summary>
            <div
              class="flex flex-col px-4 py-2 space-y-1 text-cream/90">
              <a href="#"
                class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M10 3a1 1 0 011 1v12a1 1 0 01-2 0V4a1 1 0 011-1z" />
                </svg>
                <span>My Products</span>
              </a>
              <a href="#"
                class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M7 4h6v2H7V4zm0 4h6v2H7V8zm0 4h6v2H7v-2z" />
                </svg>
                <span>Order Pesanan</span>
              </a>
              <a href="#"
                class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M5 4h10v2H5V4zm0 4h10v2H5V8zm0 4h10v2H5v-2z" />
                </svg>
                <span>Promosi & Diskon</span>
              </a>
              <a href="#"
                class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M6 3h8l1 1v12l-1 1H6l-1-1V4l1-1zm1 2v10h6V5H7z" />
                </svg>
                <span>Stock Bonsai</span>
              </a>
            </div>
          </details>
        </div>
      @endif
    @endauth
  </div>

  <!-- Fixed Footer -->
  <div class="flex-shrink-0 p-4 border-t border-cream/20">
    <a href="{{ url('/') }}"
      class="{{ $linkBase }} {{ request()->is('/') ? $activeLink : '' }}">
      <svg class="w-4 h-4" fill="none"
        stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round"
          stroke-linejoin="round"
          d="M10 19l-7-7m0 0l7-7m-7 7h18" />
      </svg>
      <span class="text-cream">Shop Front</span>
    </a>
  </div>
</div>
