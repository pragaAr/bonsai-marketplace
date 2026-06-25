<div class="pt-24 pb-16 sm:pt-28 sm:pb-20">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      @if($product)
        <a href="{{ route('product.detail', $product->slug) }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs text-primary/60 transition-colors hover:text-primary">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Kembali ke produk
        </a>
      @else
        <a href="{{ route('shop') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs text-primary/60 transition-colors hover:text-primary">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Kembali ke Toko
        </a>
      @endif
    </div>

    @if($orderSubmitted)
      <div class="mx-auto max-w-3xl rounded-3xl border border-primary/10 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-cream">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
        </div>

        <p class="mt-6 text-xs font-semibold uppercase tracking-[0.28em] text-accent">Pesanan dibuat</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-primary">Menunggu pembayaran</h1>
        <p class="mt-3 text-sm leading-6 text-primary/70">
          Nomor pesanan {{ $orderNumber }} sudah dibuat sebagai simulasi. Lanjutkan pembayaran sesuai metode yang dipilih, lalu tim seller akan memproses konfirmasi.
        </p>

        <div class="mt-8 grid gap-4 rounded-2xl border border-primary/10 bg-cream p-5 text-sm text-primary sm:grid-cols-2">
          <div>
            <p class="text-xs uppercase text-primary/45">Produk</p>
            @if($product)
              <p class="mt-1 font-semibold">{{ $product->name }}</p>
            @else
              <p class="mt-1 font-semibold">{{ count($cartItems) }} Item Keranjang</p>
            @endif
          </div>
          <div>
            <p class="text-xs uppercase text-primary/45">Total</p>
            <p class="mt-1 font-semibold">Rp {{ number_format($this->total, 0, ',', '.') }}</p>
          </div>
          <div>
            <p class="text-xs uppercase text-primary/45">Metode</p>
            <p class="mt-1 font-semibold">
              @if($paymentMethod === 'rekening_bersama')
                Rekening Bersama
              @elseif($paymentMethod === 'qris')
                QRIS
              @else
                COD
              @endif
            </p>
          </div>
          <div>
            <p class="text-xs uppercase text-primary/45">Penerima</p>
            <p class="mt-1 font-semibold">{{ $buyerName }}</p>
          </div>
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
          <a href="{{ route('profile.orders') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-cream transition-colors hover:bg-primary/90">
            Lihat history
          </a>
          <a href="{{ route('shop') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-primary/15 bg-white px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-primary/5">
            Belanja lagi
          </a>
        </div>
      </div>
    @else
      <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_420px] lg:items-start">
        <form wire:submit="submitOrder" class="space-y-6">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-accent">Checkout</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-primary sm:text-4xl">Selesaikan pembelian</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-primary/70">
              Pilih metode pembayaran dummy untuk simulasi proses beli. Data order belum disimpan ke database.
            </p>
          </div>

          <section class="rounded-2xl border border-primary/10 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-base font-semibold text-primary">Data penerima</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
              <div>
                <label for="buyerName" class="mb-2 block text-sm font-medium text-primary">Nama penerima</label>
                <input id="buyerName" type="text" wire:model.defer="buyerName" class="w-full rounded-xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" />
                @error('buyerName')
                  <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label for="whatsapp" class="mb-2 block text-sm font-medium text-primary">WhatsApp</label>
                <input id="whatsapp" type="text" wire:model.defer="whatsapp" placeholder="08123456789" class="w-full rounded-xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35" />
                @error('whatsapp')
                  <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div class="sm:col-span-2">
                <label for="address" class="mb-2 block text-sm font-medium text-primary">Alamat pengiriman</label>
                <textarea id="address" rows="4" wire:model.defer="address" class="w-full resize-none rounded-xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35"></textarea>
                @error('address')
                  <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div class="sm:col-span-2">
                <label for="notes" class="mb-2 block text-sm font-medium text-primary">Catatan opsional</label>
                <textarea id="notes" rows="3" wire:model.defer="notes" placeholder="Contoh: kirim sore hari" class="w-full resize-none rounded-xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors placeholder:text-primary/35 focus:border-primary/35"></textarea>
                @error('notes')
                  <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </section>

          <section class="rounded-2xl border border-primary/10 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-base font-semibold text-primary">Metode pembayaran</h2>
            <div class="mt-5 grid gap-3">
              <label class="flex cursor-pointer gap-4 rounded-2xl border p-4 transition-colors {{ $paymentMethod === 'rekening_bersama' ? 'border-primary bg-primary/5' : 'border-primary/10 hover:border-primary/25' }}">
                <input type="radio" wire:model.live="paymentMethod" value="rekening_bersama" class="mt-1 h-4 w-4 text-primary" />
                <span>
                  <span class="block text-sm font-semibold text-primary">Rekening Bersama</span>
                  <span class="mt-1 block text-xs leading-5 text-primary/60">Transfer ke escrow Bonsaiku. Dana diteruskan ke seller setelah pesanan dikonfirmasi.</span>
                  <span class="mt-3 block rounded-xl bg-cream px-4 py-3 text-xs font-medium text-primary">BCA 8800 1122 3344 a.n. Rekber Bonsaiku</span>
                </span>
              </label>

              <label class="flex cursor-pointer gap-4 rounded-2xl border p-4 transition-colors {{ $paymentMethod === 'qris' ? 'border-primary bg-primary/5' : 'border-primary/10 hover:border-primary/25' }}">
                <input type="radio" wire:model.live="paymentMethod" value="qris" class="mt-1 h-4 w-4 text-primary" />
                <span class="flex flex-1 flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                  <span>
                    <span class="block text-sm font-semibold text-primary">QRIS</span>
                    <span class="mt-1 block text-xs leading-5 text-primary/60">Scan QR dummy untuk simulasi pembayaran instan.</span>
                  </span>
                  <span class="grid h-24 w-24 shrink-0 grid-cols-4 gap-1 rounded-xl border border-primary/10 bg-white p-2">
                    @for($i = 0; $i < 16; $i++)
                      <span class="{{ in_array($i, [0, 1, 4, 5, 10, 11, 14, 15, 3, 6, 9, 12]) ? 'bg-primary' : 'bg-primary/10' }} rounded-sm"></span>
                    @endfor
                  </span>
                </span>
              </label>

              <label class="flex cursor-pointer gap-4 rounded-2xl border p-4 transition-colors {{ $paymentMethod === 'cod' ? 'border-primary bg-primary/5' : 'border-primary/10 hover:border-primary/25' }}">
                <input type="radio" wire:model.live="paymentMethod" value="cod" class="mt-1 h-4 w-4 text-primary" />
                <span>
                  <span class="block text-sm font-semibold text-primary">COD</span>
                  <span class="mt-1 block text-xs leading-5 text-primary/60">Bayar saat produk diterima. Tersedia untuk area tertentu sebagai simulasi.</span>
                </span>
              </label>
            </div>
            @error('paymentMethod')
              <p class="mt-3 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </section>

          <button type="submit" class="btn-lift inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-4 text-sm font-semibold text-cream transition-colors hover:bg-primary/90 sm:w-auto">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Buat pesanan
          </button>
        </form>

        <aside class="rounded-2xl border border-primary/10 bg-white p-5 shadow-sm sm:p-6 lg:sticky lg:top-24">
          <h2 class="text-base font-semibold text-primary">Ringkasan</h2>
          @if($product)
            <div class="mt-5 flex gap-4 border-b border-primary/10 pb-5">
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-24 w-24 rounded-xl object-cover" />
              <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-primary">{{ $product->name }}</p>
                <p class="mt-1 text-xs text-accent">{{ $product->category }}</p>
                <p class="mt-2 text-sm font-semibold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
              </div>
            </div>

            <div class="mt-5">
              <label for="quantity" class="mb-2 block text-sm font-medium text-primary">Jumlah</label>
              <input id="quantity" type="number" min="1" max="99" wire:model.live="quantity" class="w-28 rounded-xl border border-primary/15 bg-white px-4 py-3 text-sm text-primary outline-none transition-colors focus:border-primary/35" />
              @error('quantity')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>
          @else
            <div class="mt-5 space-y-4 border-b border-primary/10 pb-5 max-h-[300px] overflow-y-auto">
              @foreach($cartItems as $item)
                <div class="flex gap-4 items-center">
                  <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-16 w-16 rounded-xl object-cover" />
                  <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-primary">{{ $item['name'] }}</p>
                    <p class="text-xs text-primary/60 mt-0.5">{{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                  </div>
                  <p class="text-sm font-semibold text-primary shrink-0">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                </div>
              @endforeach
            </div>
          @endif

          <div class="mt-6 space-y-3 border-t border-primary/10 pt-5 text-sm">
            <div class="flex justify-between gap-4">
              <span class="text-primary/60">Subtotal</span>
              <span class="font-medium text-primary">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-primary/60">Biaya layanan</span>
              <span class="font-medium text-primary">Rp {{ number_format($this->serviceFee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between gap-4 border-t border-primary/10 pt-4 text-base">
              <span class="font-semibold text-primary">Total</span>
              <span class="font-bold text-primary">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </div>
          </div>
        </aside>
      </div>
    @endif
  </div>
</div>
