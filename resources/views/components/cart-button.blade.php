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
    <svg x-show="loading" x-cloak
      class="animate-spin {{ $iconClass }}" fill="none"
      viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12"
        r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
    </svg>
    <!-- Plus icon (idle) -->
    <svg x-show="!loading" class="{{ $iconClass }}"
      fill="none" stroke="currentColor" stroke-width="2"
      viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 4v16m8-8H4" />
    </svg>
    @if ($label)
      <span class="{{ $spanClass }}"
        x-text="loading ? 'Menambahkan…' : '{{ $label }}'">
      </span>
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
