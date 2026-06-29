<div>
  <!-- Hero Section -->
  <section id="hero-section"
    class="relative w-full h-[90vh] md:h-[100vh] overflow-hidden">
    <img src="{{ asset('images/hero2.png') }}"
      alt="Beautiful bonsai tree in a serene setting"
      class="absolute inset-0 w-full h-full object-cover" />
    <div class="hero-overlay absolute inset-0"></div>
    <div
      class="relative z-10 flex items-center h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl pt-16">
        <h1
          class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-light text-primary leading-tight">
          Dari penghobi, <br />untuk <span
            class="font-semibold">sesama penghobi.</span>
        </h1>
        <p
          class="mt-4 md:mt-6 text-sm md:text-base text-primary/70 max-w-md leading-relaxed">
          Temukan bonsai, peralatan, pot, dan media tanam
          yang direkomendasikan oleh komunitas.
        </p>
        <div class="mt-8">
          <a href="/shop" wire:navigate
            x-data="{ loading: false }" @click="loading = true"
            :class="loading ? 'opacity-80 pointer-events-none' : ''"
            class="inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-3 rounded-lg text-sm font-semibold hover:bg-primary hover:text-cream transition-colors duration-300 btn-lift">

            <!-- Spinner -->
            <x-spinner x-show="loading" x-cloak
              class="h-4 w-4 text-current" />

            <span x-show="!loading">
              Jelajahi Koleksi
            </span>

            <span x-show="loading" x-cloak>
              Memuat...
            </span>

            <!-- Arrow Icon -->
            <svg x-show="!loading" class="w-4 h-4"
              fill="none" stroke="currentColor"
              stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Tentang Kami & Filosofi — satu narasi yang mengalir -->
  <section class="bg-primary/[0.03] py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Header -->
      <div class="max-w-2xl mx-auto text-center mb-14">
        <span
          class="text-accent text-xs font-semibold uppercase tracking-wider">Tentang
          Kami</span>
        <h2
          class="text-2xl md:text-3xl font-semibold text-primary mt-4 leading-tight">
          Berawal dari Komunitas, Tumbuh Bersama
        </h2>
        <p
          class="mt-4 text-sm md:text-base text-primary/60 leading-relaxed">
          Bonsaiku lahir dari obrolan sesama penghobi yang
          ingin saling memudahkan. Kami hadir sebagai ruang
          kolektif — tempat belajar, berbagi pengalaman, dan
          mendapatkan bahan, maupun perlengkapan yang sudah
          teruji langsung di lapangan.
        </p>
      </div>

      <!-- 3 Pilar — card ringkas -->
      <div
        class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-14">
        <div
          class="group relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-primary/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
          <div
            class="absolute inset-0 bg-gradient-to-br from-green-100/0 via-amber-50/0 to-stone-50/0 opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-hover:from-green-100/80 group-hover:via-amber-50/75 group-hover:to-stone-50/85">
          </div>
          <div class="relative z-10">
            <div
              class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center text-accent text-sm font-bold mb-4 transition-colors duration-300 group-hover:bg-accent/20">
              1</div>
            <h3
              class="font-semibold text-primary text-sm mb-2 transition-colors duration-300 group-hover:text-primary">
              Ruang Edukasi Bersama</h3>
            <p
              class="text-xs text-primary/60 leading-relaxed transition-colors duration-300 group-hover:text-primary/80">
              Forum diskusi aktif untuk menjawab kendala
              perawatan dan mendampingi proses belajar, dari
              pemula hingga kolektor berpengalaman.</p>
          </div>
        </div>
        <div
          class="group relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-primary/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
          <div
            class="absolute inset-0 bg-gradient-to-br from-amber-100/0 via-green-50/0 to-stone-50/0 opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-hover:from-amber-100/80 group-hover:via-green-50/75 group-hover:to-stone-50/85">
          </div>
          <div class="relative z-10">
            <div
              class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center text-accent text-sm font-bold mb-4 transition-colors duration-300 group-hover:bg-accent/20">
              2</div>
            <h3
              class="font-semibold text-primary text-sm mb-2 transition-colors duration-300 group-hover:text-primary">
              Produk Berbasis Fungsi</h3>
            <p
              class="text-xs text-primary/60 leading-relaxed transition-colors duration-300 group-hover:text-primary/80">
              Setiap alat, kawat, dan media tanam dipilih
              dari pengalaman nyata komunitas — bukan
              sekadar dijual, tapi sudah dipakai sendiri.
            </p>
          </div>
        </div>
        <div
          class="group relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-primary/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
          <div
            class="absolute inset-0 bg-gradient-to-br from-stone-100/0 via-green-50/0 to-amber-50/0 opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-hover:from-stone-100/80 group-hover:via-green-50/75 group-hover:to-amber-50/85">
          </div>
          <div class="relative z-10">
            <div
              class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center text-accent text-sm font-bold mb-4 transition-colors duration-300 group-hover:bg-accent/20">
              3</div>
            <h3
              class="font-semibold text-primary text-sm mb-2 transition-colors duration-300 group-hover:text-primary">
              Keselarasan Visual</h3>
            <p
              class="text-xs text-primary/60 leading-relaxed transition-colors duration-300 group-hover:text-primary/80">
              Pot dan ornamen yang kami sediakan dipilih
              agar proporsional, mempertegas karakter alami
              setiap pohon tanpa berlebihan.</p>
          </div>
        </div>
      </div>

      <!-- Kutipan Filosofi — jembatan organik -->
      <div
        class="border-t border-primary/10 pt-12 max-w-2xl mx-auto text-center">
        <p
          class="text-base md:text-lg text-primary/70 leading-relaxed italic">
          "Bonsai mengajarkan bahwa keindahan tidak
          diciptakan secara instan — melainkan dirawat
          melalui perhatian kecil yang konsisten."
        </p>
        <span
          class="block mt-4 text-xs text-primary/40 uppercase tracking-wider">Filosofi
          Bonsaiku</span>
      </div>

      <!-- Stats gabungan -->
      <div
        class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div>
          <p
            class="text-2xl md:text-3xl font-bold text-primary">
            5,000+</p>
          <p
            class="text-xs text-primary/50 uppercase tracking-wider mt-2">
            Anggota Terhubung</p>
        </div>
        <div>
          <p
            class="text-2xl md:text-3xl font-bold text-primary">
            12+</p>
          <p
            class="text-xs text-primary/50 uppercase tracking-wider mt-2">
            Regional Aktif</p>
        </div>
        <div>
          <p
            class="text-2xl md:text-3xl font-bold text-primary">
            50+</p>
          <p
            class="text-xs text-primary/50 uppercase tracking-wider mt-2">
            Produk Terpilih</p>
        </div>
        <div>
          <p
            class="text-2xl md:text-3xl font-bold text-primary">
            24/7</p>
          <p
            class="text-xs text-primary/50 uppercase tracking-wider mt-2">
            Forum Diskusi</p>
        </div>
      </div>

    </div>
  </section>

  <!-- Featured Products -->
  <section
    class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
    <div
      class="absolute inset-x-4 top-8 -z-10 h-40 rounded-[2rem]">
    </div>
    <div
      class="flex items-end justify-between mb-8 md:mb-12">
      <div>
        <span
          class="text-accent text-xs font-semibold uppercase tracking-wider">Katalog</span>
        <h2
          class="text-2xl md:text-3xl font-semibold text-primary mt-3">
          Koleksi Unggulan</h2>
        <p class="text-sm text-primary/50 mt-2">
          Direkomendasikan langsung oleh komunitas</p>
      </div>
      <a href="/shop" wire:navigate
        class="text-sm text-accent hover:text-primary transition-colors hidden sm:inline-flex items-center gap-1">
        Lihat Semua
        <svg class="w-4 h-4" fill="none"
          stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24">
          <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>
    </div>

    <div
      class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8 stagger-children">
      @foreach ($featuredProducts as $product)
        <div
          class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
          <a href="/shop/product/{{ $product->slug }}"
            wire:navigate class="block">
            <div
              class="product-img-wrapper overflow-hidden bg-primary/[0.02]">
              <img src="{{ $product->image_url }}"
                alt="{{ $product->name }} bonsai"
                class="product-image-aspect w-full object-cover transition-transform duration-500 hover:scale-105"
                loading="lazy" />
            </div>
          </a>
          <div class="p-4 flex flex-col flex-1">
            <a href="/shop/product/{{ $product->slug }}"
              wire:navigate class="block flex-1">
              <h3
                class="font-semibold text-primary text-sm md:text-base leading-tight line-clamp-1 hover:text-accent transition-colors">
                {{ $product->name }}</h3>
              <p
                class="text-xs text-accent mt-1 line-clamp-1">
                {{ Str::limit($product->short_description, 30) }}
              </p>
            </a>

            {{-- <div class="flex gap-2 mt-4 pt-3 border-t border-primary/5">
              <!-- Add to Cart (Livewire dispatch) -->
              <button
                @click="requireAuth(() => $wire.$dispatch('add-to-cart', {productId: {{ $product->id }}}))"
                aria-label="Add {{ $product->name }} to cart"
                class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-primary text-cream text-xs py-2.5 px-3 rounded-lg hover:bg-opacity-90 transition-colors cursor-pointer"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Tambah</span>
              </button>
              
              <!-- WhatsApp Checkout -->
              <x-whatsapp-chat-link
                :product="$product"
                label="Chat"
                class="btn-lift flex items-center justify-center gap-1.5 bg-green-600 text-white text-xs py-2.5 px-3 rounded-lg hover:bg-green-700 transition-colors"
              />
            </div> --}}
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-8 text-center sm:hidden">
      <a href="/shop" wire:navigate
        class="text-sm text-accent hover:text-primary transition-colors">Lihat
        Semua →</a>
    </div>
  </section>

  <!-- Care Guide Teaser -->
  <section class="bg-primary/[0.03]">
    <div
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
      <div
        class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
        <div>
          <img src="{{ asset('images/journal-1.png') }}"
            alt="Bonsai care and maintenance"
            class="w-full h-64 md:h-80 object-cover rounded-2xl"
            loading="lazy" />
        </div>
        <div>
          <span
            class="text-accent text-xs font-semibold uppercase tracking-wider">Panduan</span>
          <h2
            class="text-2xl md:text-3xl font-semibold text-primary mt-3 leading-tight">
            Belajar merawat bonsai.
          </h2>
          <p
            class="mt-4 text-sm md:text-base text-primary/60 leading-relaxed">
            Panduan perawatan kami mencakup segalanya mulai
            dari jadwal penyiraman hingga pemangkasan
            musiman. Saran sederhana untuk pemula dan ahli.
          </p>
          <a href="/care-guide" wire:navigate
            x-data="{ loading: false }"
            @click="loading = true"
            :class="loading ? 'opacity-80 pointer-events-none' : ''"
            class="inline-flex items-center gap-2 mt-6 border-2 border-primary text-primary px-6 py-3 rounded-lg text-sm font-semibold hover:bg-primary hover:text-cream transition-colors duration-300 btn-lift">

            <!-- Spinner -->
            <x-spinner x-show="loading" x-cloak
              class="h-4 w-4 text-current" />

            <span x-show="!loading">
              Panduan Perawatan
            </span>

            <span x-show="loading" x-cloak>
              Memuat...
            </span>

            <!-- Arrow Icon -->
            <svg x-show="!loading" class="w-4 h-4"
              fill="none" stroke="currentColor"
              stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>
</div>
