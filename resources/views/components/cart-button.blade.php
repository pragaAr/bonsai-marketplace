@props([
    'product',
    'label' => 'Keranjang',
    'iconClass' => 'w-4 h-4',
    'spanClass' => '',
])

@auth
  <button x-data="{ loading: false }"
    @click="loading = true; $dispatch('add-to-cart', { productId: {{ $product->id }} })"
    @cart-updated.window="loading = false"
    @toast.window="loading = false" :disabled="loading"
    :class="loading ? 'opacity-60 cursor-not-allowed' : ''"
    aria-label="Add {{ $product->name }} to cart"
    {{ $attributes }}>

    <!-- Spinner (loading) -->
    <x-icons.spinner x-show="loading" x-cloak
      class="h-4 w-4 text-current {{ $iconClass }}" />

    <!-- Plus icon (idle) -->
    <svg x-show="!loading" class="{{ $iconClass }}"
      fill="none" stroke="currentColor" stroke-width="2"
      viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 4v16m8-8H4" />
    </svg>
    @if ($label)
      <span class="{{ $spanClass }}"
        x-show="!loading">{{ $label }}</span>
      <span class="{{ $spanClass }}" x-show="loading"
        x-cloak>Menambahkan…</span>
    @endif
  </button>
@else
  <a x-data="{ loading: false }" href="{{ route('login') }}"
    @click.prevent="$dispatch('toast', { 
        message: @js('Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.'), 
        duration: 3000, 
        actionText: 'Login', 
        actionUrl: @js(route('login')) 
    })"
    aria-label="Add {{ $product->name }} to cart"
    {{ $attributes }}>
    <!-- Plus icon -->
    <svg class="{{ $iconClass }}" fill="none"
      stroke="currentColor" stroke-width="2"
      viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 4v16m8-8H4" />
    </svg>
    @if ($label)
      <span class="{{ $spanClass }}"
        x-text="loading ? 'Menambahkan…' : '{{ $label }}'">
      </span>
    @endif
  </a>
@endauth
