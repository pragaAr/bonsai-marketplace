<div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <!-- Back Button -->
    <div class="mb-8">
      <a href="/shop" class="inline-flex items-center gap-1.5 text-xs text-primary/60 hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Koleksi
      </a>
    </div>

    <!-- Product Layout Grid -->
    <div class="grid md:grid-cols-2 gap-10 md:gap-16 items-start">
      
      <!-- Gallery Column -->
      <div class="space-y-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-primary/5">
          <img
            src="{{ $product->image_url }}"
            alt="{{ $product->name }} bonsai main"
            class="w-full h-auto object-cover aspect-square"
          />
        </div>
        
        <!-- Gallery Variant Thumbnails (Mocked since we have 1 image per product, but we show variant crops to wow the user) -->
        <div class="flex gap-3">
          <button class="gallery-thumb active w-20 h-20 rounded-lg overflow-hidden border-2 border-primary">
            <img src="{{ $product->image_url }}" class="w-full h-full object-cover" />
          </button>
          <button class="gallery-thumb w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent">
            <img src="{{ $product->image_url }}" class="w-full h-full object-cover filter brightness-95 opacity-80" />
          </button>
          <button class="gallery-thumb w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent">
            <img src="{{ $product->image_url }}" class="w-full h-full object-cover filter saturate-50 opacity-80" />
          </button>
        </div>
      </div>

      <!-- Info Column -->
      <div class="space-y-6">
        <div>
          <span class="text-accent text-xs font-semibold uppercase tracking-wider">{{ $product->category }}</span>
          <h1 class="text-3xl md:text-4xl font-semibold text-primary mt-2 leading-tight">{{ $product->name }}</h1>
          <p class="text-xl font-bold text-primary mt-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>

        <p class="text-sm text-primary/75 leading-relaxed">{{ $product->description }}</p>

        <!-- Specifications Grid -->
        <div class="bg-white rounded-xl p-6 border border-primary/5 shadow-sm space-y-4">
          <h3 class="text-xs font-semibold text-primary uppercase tracking-wider pb-2 border-b border-primary/5">Detail koleksi</h3>
          <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
            <div>
              <p class="text-xs text-primary/45 uppercase">Jenis</p>
              <p class="font-medium text-primary mt-0.5">{{ $product->species }}</p>
            </div>
            <div>
              <p class="text-xs text-primary/45 uppercase">Tingkat Perawatan</p>
              <p class="font-medium text-primary mt-0.5">{{ $product->care_level }}</p>
            </div>
            <div>
              <p class="text-xs text-primary/45 uppercase">Pencahayaan</p>
              <p class="font-medium text-primary mt-0.5">{{ $product->light }}</p>
            </div>
            <div>
              <p class="text-xs text-primary/45 uppercase">Penyiraman</p>
              <p class="font-medium text-primary mt-0.5">{{ $product->watering }}</p>
            </div>
            <div>
              <p class="text-xs text-primary/45 uppercase">Ukuran Pot</p>
              <p class="font-medium text-primary mt-0.5">{{ $product->pot_size }}</p>
            </div>
            <div>
              <p class="text-xs text-primary/45 uppercase">Stok</p>
              <p class="font-medium text-primary mt-0.5">1</p>
            </div>
          </div>
        </div>

        <!-- Add & Buy Actions -->
        <div class="flex flex-col gap-3 w-full">
         
          <div class="w-full">
            <x-whatsapp-chat-link
              :product="$product"
              label="Chat Penjual"
              class="btn-lift flex items-center justify-center gap-1.5 bg-green-600 text-white text-xs py-3 px-3 rounded-lg hover:bg-green-700 transition-colors w-full"
            />
          </div>

          <div class="flex gap-3 w-full"> 
            <x-cart-button
              :product="$product"
              label="Keranjang"
              iconClass="w-5 h-5"
              class="btn-lift flex-1 bg-primary text-cream py-3 rounded-xl text-sm font-semibold transition-colors cursor-pointer flex items-center justify-center gap-2 hover:bg-opacity-90"
            />
            
            <x-buy-button
              :product="$product"
              label="Beli Sekarang"
              iconClass="w-5 h-5"
              class="btn-lift flex-1 bg-[#C65A3A] text-white py-3 rounded-xl text-sm font-semibold hover:bg-[#A94B31] transition-all flex items-center justify-center gap-2"
            />
          </div>
        </div>

      </div>

    </div>

    <!-- Related Products -->
    @if(!$relatedProducts->isEmpty())
      <div class="mt-24 border-t border-primary/10 pt-16">
        <div class="flex items-end justify-between flex-wrap mb-8 md:mb-12 gap-2">
          <div>
            <h2 class="text-2xl font-semibold text-primary">Koleksi terkait</h2>
            <p class="text-xs text-primary/50 mt-1">Beragam jenis produk yang mungkin Anda sukai</p>
          </div>
          <a href="/shop" wire:navigate class="text-xs text-accent hover:text-primary transition-colors flex items-center gap-1">
            Lihat semua
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8">
          @foreach($relatedProducts as $related)
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
              <a href="/shop/product/{{ $related->slug }}" class="block">
                <div class="product-img-wrapper overflow-hidden bg-primary/[0.02]">
                  <img
                    src="{{ $related->image_url }}"
                    alt="{{ $related->name }} bonsai"
                    class="product-image-aspect w-full object-cover transition-transform duration-500 hover:scale-105"
                    loading="lazy"
                  />
                </div>
              </a>
              <div class="p-4 flex flex-col flex-1">
                <a href="/shop/product/{{ $related->slug }}" class="block flex-1">
                  <h3 class="font-semibold text-primary text-sm md:text-base leading-tight line-clamp-1 hover:text-accent transition-colors">{{ $related->name }}</h3>
                  <p class="text-xs text-accent mt-1 line-clamp-1">{{ Str::limit($related->short_description, 20) }}</p>
                  <p class="text-primary font-bold text-sm mt-2">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                </a>
                
                <div class="flex gap-2 mt-4 pt-3 border-t border-primary/5">
                  <x-cart-button
                    :product="$related"
                    label="Keranjang"
                    spanClass="hidden sm:inline"
                    class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-primary text-cream text-xs py-2.5 px-3 rounded-lg transition-colors cursor-pointer hover:bg-opacity-90"
                  />
                  <x-buy-button
                    :product="$related"
                    label="Beli"
                    class="btn-lift flex items-center justify-center gap-1.5 bg-[#C65A3A] text-white text-xs font-semibold py-2.5 px-3 rounded-lg hover:bg-[#A94B31] transition-colors"
                  />
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</div>
