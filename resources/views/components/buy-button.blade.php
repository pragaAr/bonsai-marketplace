@props([
  'product',
  'label' => 'Beli',
  'iconClass' => 'w-4 h-4',
])

<a
  @auth
  href="{{ route('checkout.product', $product->slug) }}"
  wire:navigate
  @else
  href="{{ route('login') }}"
  @click.prevent="$dispatch('toast', { message: @js('Silakan login terlebih dahulu untuk membeli produk.'), duration: 3000, actionText: 'Login', actionUrl: @js(route('login')) })"
  @endauth
  aria-label="Beli {{ $product->name }}"
  {{ $attributes }}
>
  <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 8.25h10.5l-.75 11.25h-9L6.75 8.25Z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25V6.75a3 3 0 016 0v1.5"/>
  </svg>

  @if($label)
    <span>{{ $label }}</span>
  @endif
</a>
