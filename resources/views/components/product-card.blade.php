@props(['product'])

<div
  class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full"
  wire:key="product-{{ $product->id }}">

  <!-- Product Gallery Click -->
  <a href="{{ route('product.detail', $product->slug) }}"
    wire:navigate class="block">
    <div
      class="product-img-wrapper overflow-hidden bg-primary/[0.02]">
      <img src="{{ $product->image_url }}"
        alt="{{ $product->name }} bonsai"
        class="product-image-aspect w-full object-cover transition-transform duration-500 hover:scale-105"
        loading="lazy" />
    </div>
  </a>

  <!-- Product Specs -->
  <div class="p-4 flex flex-col flex-1">
    <a href="{{ route('product.detail', $product->slug) }}"
      wire:navigate class="block flex-1">
      <h3
        class="font-semibold text-primary text-sm md:text-base leading-tight line-clamp-1 hover:text-accent transition-colors">
        {{ $product->name }}
      </h3>
      <p class="text-xs text-accent mt-1 line-clamp-1">
        {{ Str::limit($product->short_description, 20, '…') }}
      </p>
      <p class="text-primary font-bold text-sm mt-2">
        Rp {{ number_format($product->price, 0, ',', '.') }}
      </p>
    </a>

    <!-- Cart & Buy Actions -->
    <div
      class="flex flex-wrap flex-shrink gap-2 mt-4 pt-3 border-t border-primary/5">
      <x-cart-button :product="$product" label="Keranjang"
        spanClass="hidden sm:inline"
        class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-primary text-cream text-xs py-2.5 px-3 rounded-lg transition-colors cursor-pointer hover:bg-opacity-90" />

      <x-buy-button :product="$product" label="Beli"
        spanClass="hidden sm:inline"
        class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-[#C65A3A] text-white text-xs font-semibold py-2.5 px-3 rounded-lg hover:bg-[#A94B31] transition-colors" />
    </div>
  </div>
</div>
