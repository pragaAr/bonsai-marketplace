<div x-data x-init="$watch('$wire.isOpen', val => $dispatch(val ? 'cart-opened' : 'cart-closed'))">
  <!-- Backdrop -->
  <div x-show="$wire.isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="$wire.isOpen = false"
    class="fixed inset-0 bg-black/30 z-[55]"
    style="display: none;">
  </div>

  <!-- Cart Panel -->
  <aside x-show="$wire.isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform translate-x-full"
    x-transition:enter-end="transform translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="transform translate-x-0"
    x-transition:leave-end="transform translate-x-full"
    class="fixed inset-y-0 right-0 w-full max-w-md bg-cream z-[56] flex flex-col shadow-2xl"
    aria-label="Shopping cart" style="display: none;">

    <!-- Drawer Header -->
    <div
      class="flex items-center justify-between h-16 px-4 sm:px-6 border-b border-primary/10">
      <h2 class="text-lg font-semibold text-primary">
        Keranjang Anda</h2>
      <button @click="$wire.isOpen = false"
        aria-label="Close cart"
        class="p-2 -me-2 rounded-full hover:bg-primary/5 min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer">
        <svg class="w-5 h-5 text-primary" fill="none"
          stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24">
          <path stroke-linecap="round"
            d="M18 6L6 18M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Drawer Content (Items) -->
    <div class="flex-1 overflow-y-auto p-6">
      @if (empty($cartItems))
        <div
          class="flex flex-col items-center justify-center h-full text-center py-12">
          <svg class="w-16 h-16 text-primary/15 mb-4"
            fill="none" stroke="currentColor"
            stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <p class="text-primary/50 text-sm">Belum ada item
            di keranjang</p>
          <a href="/shop" @click="$wire.isOpen = false"
            class="mt-4 text-sm text-accent hover:underline">Belanja
            Sekarang</a>
        </div>
      @else
        <div class="space-y-4">
          @foreach ($cartItems as $item)
            <div
              class="flex gap-4 py-4 border-b border-primary/5 last:border-0"
              wire:key="cart-item-{{ $item['id'] }}">
              <img src="{{ $item['image'] }}"
                alt="{{ $item['name'] }}"
                class="w-20 h-20 object-cover rounded-lg flex-shrink-0" />
              <div class="flex-1 min-w-0">
                <h3
                  class="text-sm font-semibold text-primary truncate">
                  {{ $item['name'] }}</h3>
                <p class="text-sm text-accent mt-0.5">Rp
                  {{ number_format($item['price'], 0, ',', '.') }}
                </p>
                <div class="flex items-center gap-3 mt-2">
                  <button
                    wire:click="updateQuantity('{{ $item['id'] }}', {{ $item['qty'] - 1 }})"
                    aria-label="Decrease quantity"
                    class="w-7 h-7 rounded-full border border-primary/20 flex items-center justify-center text-primary hover:bg-primary/5 text-sm cursor-pointer">-</button>
                  <span
                    class="text-sm font-medium text-primary w-5 text-center">{{ $item['qty'] }}</span>
                  <button
                    wire:click="updateQuantity('{{ $item['id'] }}', {{ $item['qty'] + 1 }})"
                    aria-label="Increase quantity"
                    class="w-7 h-7 rounded-full border border-primary/20 flex items-center justify-center text-primary hover:bg-primary/5 text-sm cursor-pointer">+</button>
                </div>
              </div>
              <button
                wire:click="removeFromCart('{{ $item['id'] }}')"
                aria-label="Remove {{ $item['name'] }}"
                class="self-start p-1 text-primary/30 hover:text-red-500 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none"
                  stroke="currentColor" stroke-width="2"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                    d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Drawer Footer -->
    @if (!empty($cartItems))
      <div class="border-t border-primary/10 p-6 bg-cream">
        <div class="flex justify-between items-center mb-4">
          <span
            class="text-sm text-primary/60">Subtotal</span>
          <span class="text-lg font-bold text-primary">Rp
            {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

        <div class="space-y-2">
          <!-- Checkout -->
          <button wire:click="checkout"
            wire:loading.attr="disabled"
            class="w-full bg-primary text-cream py-3 rounded-lg text-sm font-semibold hover:bg-opacity-90 transition-colors cursor-pointer duration-200 btn-lift flex items-center justify-center gap-2">

            <!-- Spinner -->
            <x-spinner wire:loading wire:target="checkout"
              class="h-4 w-4 text-cream" />

            <span wire:loading.remove
              wire:target="checkout">Buat Pesanan</span>
            <span wire:loading
              wire:target="checkout">Memproses...</span>
          </button>

          <!-- Download PDF Invoice -->
          <button wire:click="downloadInvoice"
            wire:loading.attr="disabled"
            class="w-full border-2 border-accent text-primary py-3 rounded-lg text-sm font-semibold hover:bg-accent hover:text-cream transition-colors cursor-pointer duration-200 btn-lift flex items-center justify-center gap-2">

            <!-- Spinner -->
            <x-spinner wire:loading
              wire:target="downloadInvoice"
              class="h-4 w-4 text-current" />

            <!-- Invoice Icon -->
            <svg wire:loading.remove
              wire:target="downloadInvoice" class="w-4 h-4"
              fill="none" stroke="currentColor"
              stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span wire:loading.remove
              wire:target="downloadInvoice">Unduh Invoice
              (PDF)</span>
            <span wire:loading
              wire:target="downloadInvoice">Mengunduh...</span>
          </button>

          <!-- Clear Cart -->
          <button wire:click="clearCart"
            wire:loading.attr="disabled"
            class="w-full text-xs text-primary/40 hover:text-primary transition-colors cursor-pointer py-2 flex items-center justify-center gap-1.5">

            <!-- Spinner -->
            <x-spinner wire:loading wire:target="clearCart"
              class="h-4 w-4 text-current" />

            <span wire:loading.remove
              wire:target="clearCart">Kosongkan
              Keranjang</span>
            <span wire:loading
              wire:target="clearCart">Mengosongkan...</span>
          </button>
        </div>
      </div>
    @endif
  </aside>
</div>
