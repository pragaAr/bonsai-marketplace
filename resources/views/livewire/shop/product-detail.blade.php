@php
  $referer = request()->headers->get('referer');
  $refererPath = is_string($referer)
      ? parse_url($referer, PHP_URL_PATH)
      : null;
  $refererSellerId = null;

  if (
      is_string($refererPath) &&
      preg_match(
          '#/seller/shop/(\d+)/?$#',
          $refererPath,
          $matches,
      )
  ) {
      $refererSellerId = (int) $matches[1];
  }

  $backToSeller =
      $refererSellerId === (int) $product->seller_id;
  $backUrl = $backToSeller
      ? route('seller.shop', $product->seller_id)
      : route('shop');
  $backLabel = $backToSeller
      ? 'Kembali ke Toko'
      : 'Kembali ke Koleksi';
@endphp

<div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <!-- Back Button -->
    <div class="mb-8">
      <a href="{{ $backUrl }}" wire:navigate
        x-data="{ loading: false }" @click="loading = true"
        :class="loading ? 'opacity-80 pointer-events-none' : ''"
        class="inline-flex items-center gap-1.5 text-xs text-primary/60 hover:text-primary transition-colors">
        <x-icons.arrow-left x-show="!loading"
          class="w-4 h-4" />

        <x-icons.spinner x-show="loading" x-cloak
          class="h-4 w-4 text-current" />

        {{ $backLabel }}
      </a>
    </div>

    <!-- Product Layout Grid -->
    <div
      class="grid md:grid-cols-2 gap-7 md:gap-10 items-start">

      <!-- Gallery Column -->
      @php
        $galleryImages = $product
            ->getMedia('images')
            ->take(4)
            ->values();
        $mainImage =
            $galleryImages->first()?->getUrl() ??
            $product->image_url;
      @endphp

      <div class="space-y-4" x-data="{ mainImage: @js($mainImage), activeImage: 0 }">
        <div
          class="bg-white rounded-2xl overflow-hidden shadow-sm border border-primary/5">
          <img :src="mainImage"
            alt="{{ $product->name }} bonsai main"
            class="w-full h-full object-contain aspect-square p-4" />
        </div>

        @if ($galleryImages->count() > 1)
          <div class="flex gap-3">
            @foreach ($galleryImages as $index => $media)
              <button type="button"
                @click="mainImage = @js($media->getUrl()); activeImage = {{ $index }}"
                :class="activeImage === {{ $index }} ?
                    'border-primary' : 'border-transparent'"
                class="gallery-thumb w-20 h-20 rounded-lg overflow-hidden border-2 transition-colors cursor-pointer">
                <img src="{{ $media->getUrl() }}"
                  alt="{{ $product->name }} preview {{ $index + 1 }}"
                  class="w-full h-full object-cover" />
              </button>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Info Column -->
      <div class="space-y-6">
        <div>
          <span
            class="text-accent text-xs font-semibold uppercase tracking-wider">{{ $product->category->name }}</span>
          <h1
            class="text-3xl md:text-4xl font-semibold text-primary mt-2 leading-tight">
            {{ $product->name }}</h1>
          <p class="text-xl font-bold text-primary mt-3">Rp
            {{ number_format($product->price, 0, ',', '.') }}
          </p>

          <p
            class="text-sm text-primary/75 leading-relaxed">
            {{ $product->short_description }}</p>
        </div>

        <!-- Specifications Grid -->
        <div
          class="bg-white rounded-xl p-6 border border-primary/5 shadow-sm space-y-4">
          <h3
            class="text-xs font-semibold text-primary uppercase tracking-wider pb-2 border-b border-primary/5">
            Detail produk</h3>
          <div
            class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
            @if ($product->isPlant())
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Jenis</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ optional($product->productable->species)->scientific_name ?? '' }}
                </p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Tingkat Perawatan</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->care_level }}
                </p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Pencahayaan</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->light }}</p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Penyiraman</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->watering }}</p>
              </div>
              @if ($product->productable->pot_size)
                <div>
                  <p
                    class="text-xs text-primary/45 uppercase">
                    Ukuran Pot</p>
                  <p
                    class="font-medium text-primary mt-0.5">
                    {{ $product->productable->pot_size }}
                  </p>
                </div>
              @endif
            @elseif ($product->isPot())
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Bahan</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->material }}</p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Bentuk</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->shape }}</p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Dimensi</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->dimensions }}
                </p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Warna</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->color }}</p>
              </div>
            @elseif ($product->isMedia())
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Tipe</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->type }}</p>
              </div>
              @if ($product->productable->weight)
                <div>
                  <p
                    class="text-xs text-primary/45 uppercase">
                    Berat</p>
                  <p
                    class="font-medium text-primary mt-0.5">
                    {{ $product->productable->weight }}</p>
                </div>
              @endif
              @if ($product->productable->volume)
                <div>
                  <p
                    class="text-xs text-primary/45 uppercase">
                    Volume</p>
                  <p
                    class="font-medium text-primary mt-0.5">
                    {{ $product->productable->volume }}</p>
                </div>
              @endif
            @elseif ($product->isFertilizer())
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Tipe</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->type }}</p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Formulasi</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->form }}</p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Berat/Isi</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->weight }}</p>
              </div>
            @elseif ($product->isTool())
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Bahan</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->material }}</p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Merek</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->brand }}</p>
              </div>
              <div>
                <p
                  class="text-xs text-primary/45 uppercase">
                  Berat</p>
                <p class="font-medium text-primary mt-0.5">
                  {{ $product->productable->weight }}</p>
              </div>
            @endif
            <div>
              <p class="text-xs text-primary/45 uppercase">
                Stok</p>
              <p class="font-medium text-primary mt-0.5">
                {{ $product->stockLabel() }}
              </p>
            </div>
          </div>

          @if ($product->description != '')
            <div class="border-t border-primary/10 pt-4">
              <p
                class="text-xs font-semibold text-primary uppercase tracking-wider mb-2">
                Deskripsi</p>
              <p
                class="text-sm text-primary/75 leading-relaxed">
                {{ $product->description }}</p>
            </div>
          @endif

          @if ($product->tags->isNotEmpty())
            <div class="border-t border-primary/10 pt-4">
              <h3
                class="text-xs font-semibold text-primary uppercase tracking-wider mb-2">
                Tags Produk</h3>
              <div class="flex flex-wrap gap-2">
                @foreach ($product->tags as $tag)
                  <span
                    class="px-2.5 py-1 rounded-full bg-primary/[0.06] text-primary text-xs">
                    #{{ $tag->name }}
                  </span>
                @endforeach
              </div>
            </div>
          @endif

          <div
            class="flex items-center gap-2 border-t border-primary/10 pt-4 text-sm">
            <span class="text-primary/60">Dijual oleh</span>
            <span class="font-semibold text-primary">
              {{ $product->seller?->sellerRequest?->store_name ?? ($product->seller?->name ?? 'Toko tidak tersedia') }}
            </span>
            @if ($product->seller)
              <a href="{{ route('seller.shop', $product->seller_id) }}"
                wire:navigate x-data="{ loading: false }"
                @click="loading = true"
                :class="loading ? 'opacity-80 pointer-events-none' :
                    ''"
                class="ml-auto inline-flex items-center gap-1 text-xs font-semibold text-accent hover:underline">
                Kunjungi Toko

                <!-- Arrow Icon -->
                <x-icons.arrow-right x-show="!loading"
                  aria-hidden="true" class="w-4 h-4" />

                <!-- Spinner -->
                <x-icons.spinner x-show="loading" x-cloak
                  aria-hidden="true"
                  class="h-4 w-4 text-current" />
              </a>
            @endif
          </div>

        </div>

        <!-- Add & Buy Actions -->
        <div class="flex flex-col gap-3 w-full">

          <div class="flex gap-3 w-full">
            <x-cart-button :product="$product"
              label="Keranjang" iconClass="w-5 h-5"
              class="btn-lift flex-1 bg-primary text-cream py-3 rounded-xl text-sm font-semibold transition-colors cursor-pointer flex items-center justify-center gap-2 hover:bg-opacity-90" />

            <x-buy-button :product="$product"
              label="Beli Sekarang" iconClass="w-5 h-5"
              class="btn-lift flex-1 bg-[#C65A3A] text-white py-3 rounded-xl text-sm font-semibold hover:bg-[#A94B31] transition-all flex items-center justify-center gap-2" />
          </div>
        </div>

      </div>

    </div>

    <!-- Related Products -->
    @if (!$relatedProducts->isEmpty())
      <div class="mt-24 border-t border-primary/10 pt-16">
        <div
          class="flex items-end justify-between flex-wrap mb-8 md:mb-12 gap-2">
          <div>
            <h2 class="text-2xl font-semibold text-primary">
              Koleksi terkait</h2>
            <p class="text-xs text-primary/50 mt-1">Beragam
              produk yang mungkin Anda sukai</p>
          </div>
          <a href="/shop" wire:navigate
            x-data="{ loading: false }"
            @click="loading = true"
            :class="loading ? 'opacity-80 pointer-events-none' : ''"
            class="text-xs text-accent hover:text-primary transition-colors flex items-center gap-1">
            Lihat semua

            <x-icons.arrow-right x-show="!loading"
              class="w-4 h-4" />

            <x-icons.spinner x-show="loading" x-cloak
              class="h-4 w-4 text-current" />
          </a>
        </div>

        <div
          class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8">
          @foreach ($relatedProducts as $related)
            <div
              class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
              <a href="{{ route('product.detail', $related->slug) }}"
                class="block">
                <div
                  class="product-img-wrapper overflow-hidden bg-primary/[0.02]">
                  <img src="{{ $related->image_url }}"
                    alt="{{ $related->name }} bonsai"
                    class="product-image-aspect w-full object-cover transition-transform duration-500 hover:scale-105"
                    loading="lazy" />
                </div>
              </a>
              <div class="p-4 flex flex-col flex-1">
                <a href="{{ route('product.detail', $related->slug) }}"
                  class="block flex-1">
                  <h3
                    class="font-semibold text-primary text-sm md:text-base leading-tight line-clamp-1 hover:text-accent transition-colors">
                    {{ $related->name }}</h3>
                  <p
                    class="text-xs text-accent mt-1 line-clamp-1">
                    {{ Str::limit($related->short_description, 20) }}
                  </p>
                  <p
                    class="text-primary font-bold text-sm mt-2">
                    Rp
                    {{ number_format($related->price, 0, ',', '.') }}
                  </p>
                </a>

                <div
                  class="flex gap-2 mt-4 pt-3 border-t border-primary/5">
                  <x-cart-button :product="$related"
                    label="Keranjang"
                    spanClass="hidden sm:inline"
                    class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-primary text-cream text-xs py-2.5 px-3 rounded-lg transition-colors cursor-pointer hover:bg-opacity-90" />
                  <x-buy-button :product="$related"
                    label="Beli"
                    spanClass="hidden sm:inline"
                    class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-[#C65A3A] text-white text-xs font-semibold py-2.5 px-3 rounded-lg hover:bg-[#A94B31] transition-colors" />
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</div>
