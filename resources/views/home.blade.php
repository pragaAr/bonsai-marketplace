<x-app-layout>
  <!-- Hero Section -->
  <section id="hero-section"
    class="relative w-full h-[85vh] md:h-[90vh] overflow-hidden">
    <img src="{{ asset('static_backup/images/hero.png') }}"
      alt="Beautiful bonsai tree in a serene setting"
      class="absolute inset-0 w-full h-full object-cover" />
    <div class="hero-overlay absolute inset-0"></div>
    <div
      class="relative z-10 flex items-center h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-lg pt-16">
        <h1
          class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-light text-primary leading-tight">
          Bring Timeless<br />Nature to<br /><span
            class="font-semibold">Your Space.</span>
        </h1>
        <p
          class="mt-4 md:mt-6 text-sm md:text-base text-primary/70 max-w-sm leading-relaxed">
          Curated bonsai for those who appreciate the quiet
          beauty of living art.
        </p>
        <div class="flex flex-wrap gap-3 mt-6 md:mt-8">
          <a href="/shop"
            class="inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-3 rounded-lg text-sm font-semibold hover:bg-primary hover:text-cream transition-colors duration-300 btn-lift">
            Explore Collection
            <svg class="w-4 h-4" fill="none"
              stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
          <a href="/care-guide"
            class="inline-flex items-center text-sm text-primary/60 hover:text-primary transition-colors px-2 py-3">
            Care Guide →
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured Products -->
  <section
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
    <div
      class="flex items-end justify-between mb-8 md:mb-12">
      <div>
        <h2
          class="text-2xl md:text-3xl font-semibold text-primary">
          Featured Collection</h2>
        <p class="text-sm text-primary/50 mt-2">
          Hand-selected for your space</p>
      </div>
      <a href="/shop"
        class="text-sm text-accent hover:text-primary transition-colors hidden sm:inline-flex items-center gap-1">
        View all
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
            class="block">
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
              class="block flex-1">
              <h3
                class="font-semibold text-primary text-sm md:text-base leading-tight line-clamp-1 hover:text-accent transition-colors">
                {{ $product->name }}</h3>
              <p
                class="text-xs text-accent mt-1 line-clamp-1">
                {{ Str::limit($product->short_description, 20) }}
              </p>
              <p
                class="text-primary font-bold text-sm mt-2">
                Rp
                {{ number_format($product->price, 0, ',', '.') }}
              </p>
            </a>

            <div
              class="flex gap-2 mt-4 pt-3 border-t border-primary/5">
              <!-- Add to Cart (Livewire dispatch) -->
              <x-cart-button :product="$product"
                label="Cart"
                class="btn-lift flex-1 flex items-center justify-center gap-1.5 bg-primary text-cream text-xs py-2.5 px-3 rounded-lg transition-colors hover:bg-opacity-90" />

              <x-whatsapp-chat-link :product="$product"
                label="Chat"
                class="btn-lift flex items-center justify-center gap-1.5 bg-green-600 text-white text-xs py-2.5 px-3 rounded-lg hover:bg-green-700 transition-colors" />
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-8 text-center sm:hidden">
      <a href="/shop"
        class="text-sm text-accent hover:text-primary transition-colors">View
        all products →</a>
    </div>
  </section>

  <!-- Brand Intro -->
  <section class="bg-primary/[0.03]">
    <div
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
      <div class="max-w-2xl mx-auto text-center">
        <span
          class="text-accent text-xs font-semibold uppercase tracking-wider">Our
          Philosophy</span>
        <h2
          class="text-2xl md:text-3xl font-semibold text-primary mt-4 leading-tight">
          Living art, cultivated with patience.
        </h2>
        <p
          class="mt-6 text-sm md:text-base text-primary/60 leading-relaxed">
          Each bonsai in our collection is carefully
          nurtured to embody the harmony between nature and
          human craft. We believe that bringing a bonsai
          into your space is not just about decoration —
          it's about cultivating a practice of patience,
          attention, and quiet beauty.
        </p>
        <div class="flex justify-center gap-8 mt-10">
          <div class="text-center">
            <p
              class="text-2xl md:text-3xl font-bold text-primary">
              200+</p>
            <p class="text-xs text-primary/50 mt-1">Trees
              Curated</p>
          </div>
          <div class="text-center">
            <p
              class="text-2xl md:text-3xl font-bold text-primary">
              18</p>
            <p class="text-xs text-primary/50 mt-1">Species
            </p>
          </div>
          <div class="text-center">
            <p
              class="text-2xl md:text-3xl font-bold text-primary">
              5yr</p>
            <p class="text-xs text-primary/50 mt-1">Avg. Age
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Care Guide Teaser -->
  <section
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
    <div
      class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
      <div>
        <img
          src="{{ asset('static_backup/images/journal-1.png') }}"
          alt="Bonsai care and maintenance"
          class="w-full h-64 md:h-80 object-cover rounded-2xl"
          loading="lazy" />
      </div>
      <div>
        <span
          class="text-accent text-xs font-semibold uppercase tracking-wider">Learn</span>
        <h2
          class="text-2xl md:text-3xl font-semibold text-primary mt-3 leading-tight">
          New to bonsai? Start here.
        </h2>
        <p
          class="mt-4 text-sm md:text-base text-primary/60 leading-relaxed">
          Our care guide covers everything from watering
          schedules to seasonal pruning. Simple advice for
          beginners and experienced growers alike.
        </p>
        <a href="/care-guide"
          class="inline-flex items-center gap-2 mt-6 border-2 border-primary text-primary px-6 py-3 rounded-lg text-sm font-semibold hover:bg-primary hover:text-cream transition-colors duration-300 btn-lift">
          Read Care Guide
        </a>
      </div>
    </div>
  </section>
</x-app-layout>
