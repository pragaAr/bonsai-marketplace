<!-- resources/views/components/sidebar.blade.php -->
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
            class="{{ $linkBase }} {{ request()->routeIs('admin.*') ? $activeLink : '' }}">
            <svg class="w-4 h-4 text-cream" viewBox="0 0 20 20"
              fill="currentColor" aria-hidden="true">
              <path
                d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H4zm1 2h10v2H5V5zm0 4h10v6H5V9z" />
            </svg>
            <span
              class="font-medium text-cream">Dashboard</span>
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
                  <path d="M4 4h12v4H4V4zm0 6h8v4H4v-4z" />
                </svg>
                <span class="text-cream">Store
                  Management</span>
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
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M3 9.5A2.5 2.5 0 015.5 7h9A2.5 2.5 0 0117 9.5v3A2.5 2.5 0 0114.5 15h-9A2.5 2.5 0 013 12.5v-3z" />
                </svg>
                <span>Manage Products</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M10 2a5 5 0 00-5 5v2H4a2 2 0 00-2 2v3a2 2 0 002 2h12a2 2 0 002-2v-3a2 2 0 00-2-2h-1V7a5 5 0 00-5-5z" />
                </svg>
                <span>Manage Users</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M6 2a1 1 0 00-1 1v2h10V3a1 1 0 00-1-1H6zm-2 6h14v7a1 1 0 01-1 1H5a1 1 0 01-1-1V8z" />
                </svg>
                <span>Order Reports</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M5 4h10v2H5V4zm0 4h10v2H5V8zm0 4h10v2H5v-2z" />
                </svg>
                <span>Category Settings</span>
              </a>
            </div>
          </details>

          <div
            class="px-3 pt-2 text-[11px] uppercase tracking-[0.2em] text-cream/60">
            Seller Management</div>
          <details class="group rounded bg-white/5" open>
            <summary
              class="flex items-center justify-between border-l-2 border-transparent px-3 py-2 rounded-r cursor-pointer transition-colors duration-150 hover:border-accent hover:bg-accent/10 hover:text-white">
              <span class="flex items-center gap-3">
                <svg class="w-4 h-4 text-cream"
                  viewBox="0 0 20 20" fill="currentColor"
                  aria-hidden="true">
                  <path d="M4 4h12v12H4V4zm2 2v8h8V6H6z" />
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
              <a href="#" class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M5 4h10v2H5V4zm0 4h10v2H5V8zm0 4h10v2H5v-2z" />
                </svg>
                <span>Daftar Seller</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M4 5h12v2H4V5zm0 4h12v2H4V9zm0 4h12v2H4v-2z" />
                </svg>
                <span>Payout Requests</span>
              </a>
              <a href="#" class="{{ $subLinkBase }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"
                  fill="currentColor" aria-hidden="true">
                  <path
                    d="M10 2a5 5 0 00-5 5v2H4a2 2 0 00-2 2v3a2 2 0 002 2h12a2 2 0 002-2v-3a2 2 0 00-2-2h-1V7a5 5 0 00-5-5z" />
                </svg>
                <span>Seller Reviews</span>
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
      <svg class="w-4 h-4 text-cream" viewBox="0 0 20 20"
        fill="currentColor" aria-hidden="true">
        <path
          d="M10 2L2 8v8a1 1 0 001 1h5v-5h4v5h5a1 1 0 001-1V8l-8-6z" />
      </svg>
      <span class="text-cream">Shop Front</span>
    </a>
  </div>
</div>
