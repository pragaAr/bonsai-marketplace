<div>
  <div class="pt-16">

    <!-- Header -->
    <section
      class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4 md:pt-12 md:pb-8">
      <span
        class="text-accent text-xs font-semibold uppercase tracking-wider">Cerita
        & Wawasan</span>
      <h1
        class="text-2xl md:text-3xl lg:text-4xl font-semibold text-primary mt-3 font-light">
        Artikel</h1>
      <p
        class="mt-3 text-sm md:text-base text-primary/50 max-w-lg">
        Catatan seputar kegiatan, teknik budi daya,
        estetika, dan seni merawat bonsai dari komunitas.
      </p>
    </section>

    <!-- Daftar Artikel -->
    <section
      class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 md:pb-24">
      <div class="space-y-6 stagger-children">
        @foreach ($journals as $entry)
          <article
            class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-primary/5">
            <div class="grid md:grid-cols-3 gap-0">
              <div
                class="md:col-span-1 bg-primary/[0.02] overflow-hidden">
                <a href="{{ route('article.detail', $entry->slug) }}"
                  wire:navigate class="block h-full">
                  <img src="{{ $entry->image_url }}"
                    alt="{{ $entry->title }}"
                    class="w-full h-48 md:h-full object-cover transition-transform duration-500 hover:scale-105"
                    loading="lazy" />
                </a>
              </div>
              <div
                class="md:col-span-2 p-6 md:p-8 flex flex-col justify-center">
                <div class="flex items-center gap-2">
                  <span
                    class="bg-accent/10 text-accent text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded">
                    {{ $entry->category }}
                  </span>
                  <time
                    class="text-xs text-primary/45 font-semibold uppercase tracking-wider">
                    {{ \Carbon\Carbon::parse($entry->published_date)->locale('id')->translatedFormat('d F Y') }}
                  </time>
                </div>
                <a href="{{ route('article.detail', $entry->slug) }}"
                  wire:navigate class="block group">
                  <h2
                    class="text-lg md:text-xl font-semibold text-primary mt-2 leading-snug group-hover:text-accent transition-colors">
                    {{ $entry->title }}</h2>
                </a>
                <p
                  class="text-sm text-primary/60 mt-3 leading-relaxed">
                  {{ $entry->excerpt }}</p>
                <a href="{{ route('article.detail', $entry->slug) }}"
                  wire:navigate x-data="{ loading: false }"
                  @click="loading = true"
                  class="inline-flex items-center gap-1 text-sm text-accent hover:text-primary transition-colors mt-4 cursor-pointer">

                  <x-icons.spinner x-show="loading" x-cloak
                    class="h-4 w-4 text-current" />

                  <span x-show="!loading">
                    Baca artikel
                  </span>

                  <span x-show="loading" x-cloak>
                    Memuat...
                  </span>

                  <svg x-show="!loading" class="w-4 h-4"
                    fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                      d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </section>

  </div>
</div>
