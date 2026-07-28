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
  <x-icons.spinner x-show="loading" x-cloak
    class="h-4 w-4 text-current {{ $iconClass }}" />

  <!-- Shopping Bag Icon (idle) -->
  <svg x-show="!loading" class="{{ $iconClass }}"
    fill="none" stroke="currentColor" stroke-width="2"
    viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
  </svg>

  @if ($label)
    <span class="{{ $spanClass }}"
      x-show="!loading">{{ $label }}</span>
    <span class="{{ $spanClass }}" x-show="loading"
      x-cloak>Memproses…</span>
  @endif
</a>
