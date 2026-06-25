<button wire:key="cart-btn-{{ $count }}" @click="$dispatch('open-cart')" aria-label="Open cart" class="rounded-full hover:bg-primary/5 transition-colors relative min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer">
  <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
  </svg>
  @if($count > 0)
    <span wire:key="cart-badge-span-{{ $count }}" class="absolute -top-0.5 -right-0.5 bg-accent text-primary text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center animate-cart-pop">
      {{ $count > 99 ? '99+' : $count }}
    </span>
  @endif
</button>
