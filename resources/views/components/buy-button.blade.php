@props([
    'product',
    'label' => 'Beli',
    'iconClass' => 'w-4 h-4',
    'spanClass' => '',
])

<a x-data="{ loading: false }"
  @auth
href="{{ route('checkout.product', $product->slug) }}"
  wire:navigate
  @click="loading = true"
  @else
  href="{{ route('login') }}"
  @click.prevent="$dispatch('toast', { message: @js('Silakan login terlebih dahulu untuk membeli produk.'), duration: 3000, actionText: 'Login', actionUrl: @js(route('login')) })" @endauth
  aria-label="Beli {{ $product->name }}"
  :class="loading ?
      'opacity-60 cursor-not-allowed pointer-events-none' :
      ''"
  {{ $attributes }}>

  <!-- Spinner (loading) -->
  <x-spinner x-show="loading" x-cloak
    class="h-4 w-4 text-current {{ $iconClass }}" />

  <!-- Shopping Bag Icon (idle) -->
  <svg x-show="!loading" class="{{ $iconClass }}"
    fill="none" stroke="currentColor" stroke-width="2"
    viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M6.75 8.25h10.5l-.75 11.25h-9L6.75 8.25Z" />
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M9 8.25V6.75a3 3 0 016 0v1.5" />
  </svg>

  @if ($label)
    <span class="{{ $spanClass }}"
      x-show="!loading">{{ $label }}</span>
    <span class="{{ $spanClass }}" x-show="loading"
      x-cloak>Memproses…</span>
  @endif
</a>
