@props([
  'product',
  'label' => 'Beli',
  'iconClass' => 'w-4 h-4',
])

<a
  x-data="{ loading: false }"
  @auth
  href="{{ route('checkout.product', $product->slug) }}"
  wire:navigate
  @click="loading = true"
  @else
  href="{{ route('login') }}"
  @click.prevent="$dispatch('toast', { message: @js('Silakan login terlebih dahulu untuk membeli produk.'), duration: 3000, actionText: 'Login', actionUrl: @js(route('login')) })"
  @endauth
  aria-label="Beli {{ $product->name }}"
  :class="loading ? 'opacity-60 cursor-not-allowed pointer-events-none' : ''"
  {{ $attributes }}
>
  <!-- Spinner (loading) -->
  <svg x-show="loading" x-cloak class="animate-spin {{ $iconClass }}" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
  </svg>

  <!-- Shopping Bag Icon (idle) -->
  <svg x-show="!loading" class="{{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 8.25h10.5l-.75 11.25h-9L6.75 8.25Z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25V6.75a3 3 0 016 0v1.5"/>
  </svg>

  @if($label)
    <span x-text="loading ? 'Memproses…' : '{{ $label }}'"></span>
  @endif
</a>

