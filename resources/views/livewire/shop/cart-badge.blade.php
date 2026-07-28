<button wire:key="cart-btn-{{ $count }}"
  @click="$dispatch('open-cart')" aria-label="Open cart"
  class="rounded-full hover:bg-primary/5 transition-colors relative min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer">
  <svg class="w-5 h-5 text-primary" fill="none"
    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6M10 21a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4z" />
  </svg>
  @if ($count > 0)
    <span wire:key="cart-badge-span-{{ $count }}"
      x-data="{ pop: false }"
      x-on:cart-updated.window="pop = true; setTimeout(() => pop = false, 600)"
      :class="pop ? 'animate-cart-pop' : ''"
      class="absolute -top-0.5 -right-0.5 bg-accent text-primary text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
      {{ $count > 99 ? '99+' : $count }}
    </span>
  @endif
</button>
