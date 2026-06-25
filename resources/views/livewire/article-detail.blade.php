<div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <!-- Back Button -->
    <div class="mb-8">
      <a href="/article" wire:navigate class="inline-flex items-center gap-1.5 text-xs text-primary/60 hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Artikel
      </a>
    </div>

    <!-- Article Layout Grid -->
    <div class="grid md:grid-cols-2 gap-10 md:gap-16 items-start">
      
      <!-- Gallery Column -->
      <div class="space-y-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-primary/5">
          <img
            src="{{ $article->image_url }}"
            alt="{{ $article->title }}"
            class="w-full h-auto object-cover aspect-video md:aspect-[4/3]"
          />
        </div>

        <!-- Specifications-style Grid for Article Details -->
        <div class="bg-white rounded-xl p-6 border border-primary/5 shadow-sm space-y-4">
          <h3 class="text-xs font-semibold text-primary uppercase tracking-wider pb-2 border-b border-primary/5">Detail Artikel</h3>
          <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
            <div>
              <p class="text-xs text-primary/45 uppercase">Penulis</p>
              <p class="font-medium text-primary mt-0.5">{{ $article->author ? $article->author->name : 'Komunitas Bonsaiku' }}</p>
            </div>
            <div>
              <p class="text-xs text-primary/45 uppercase">Waktu Baca</p>
              <p class="font-medium text-primary mt-0.5">3 Menit</p>
            </div>
            <div>
              <p class="text-xs text-primary/45 uppercase">Kategori</p>
              <p class="font-medium text-primary mt-0.5">{{ $article->category }}</p>
            </div>
            <div>
              <p class="text-xs text-primary/45 uppercase">Tanggal Rilis</p>
              <p class="font-medium text-primary mt-0.5">{{ \Carbon\Carbon::parse($article->published_date)->locale('id')->translatedFormat('d F Y') }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Info/Content Column -->
      <div class="space-y-6">
        <div>
          <span class="text-accent text-xs font-semibold uppercase tracking-wider">{{ $article->category }}</span>
          <h1 class="text-3xl md:text-4xl font-semibold text-primary mt-2 leading-tight">{{ $article->title }}</h1>
          <p class="text-xs text-primary/40 mt-2">Diterbitkan pada {{ \Carbon\Carbon::parse($article->published_date)->locale('id')->translatedFormat('d F Y') }}</p>
        </div>

        <!-- Excerpt / Intro (Wabi-Sabi style callout) -->
        <div class="border-l-4 border-accent pl-4 py-1.5 italic text-primary/70 text-base md:text-lg leading-relaxed bg-accent/5 rounded-r-lg">
          {{ $article->excerpt }}
        </div>

        <!-- Main Article Content paragraphs -->
        <div class="text-sm md:text-base text-primary/80 leading-relaxed space-y-6">
          @if($article->content)
            @foreach(explode("\n\n", $article->content) as $paragraph)
              <p>{{ $paragraph }}</p>
            @endforeach
          @else
            <p>Konten artikel belum tersedia.</p>
          @endif
        </div>

        <!-- Action / Share Row -->
        <div class="pt-6 border-t border-primary/5 flex items-center justify-between gap-4">
          <a href="/article" wire:navigate class="btn-lift inline-flex items-center gap-2 bg-primary text-cream px-5 py-3 rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Semua Artikel
          </a>

          <!-- Share Button with Toast -->
          <button 
            type="button"
            onclick="navigator.clipboard.writeText(window.location.href); window.showToast({ message: 'Tautan artikel berhasil disalin ke papan klip!', duration: 3000 });"
            class="btn-lift inline-flex items-center gap-2 border-2 border-primary/10 text-primary/70 px-5 py-3 rounded-lg text-xs font-semibold hover:bg-primary/5 hover:text-primary transition-all cursor-pointer"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.828-2.414m0 0a3 3 0 10-3.62-1.09l-4.829 2.414m0 0a3 3 0 100 4.284l4.829 2.414m0 0a3 3 0 103.62-1.09"/>
            </svg>
            Bagikan
          </button>
        </div>
      </div>

    </div>

    <!-- Related Articles -->
    @if(!$relatedArticles->isEmpty())
      <div class="mt-24 border-t border-primary/10 pt-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4 mb-8 md:mb-12">
          <div>
            <h2 class="text-2xl font-semibold text-primary">Artikel Terkait</h2>
            <p class="text-xs text-primary/50 mt-1">Tulisan menarik lainnya di kategori yang sama</p>
          </div>
          <a href="/article" wire:navigate class="text-xs text-accent hover:text-primary transition-colors flex items-center gap-1 self-start sm:self-auto">
            Lihat semua
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          @foreach($relatedArticles as $related)
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full border border-primary/5">
              <a href="/article/{{ $related->slug }}" wire:navigate class="block">
                <div class="overflow-hidden bg-primary/[0.02]">
                  <img
                    src="{{ $related->image_url }}"
                    alt="{{ $related->title }}"
                    class="w-full h-48 object-cover transition-transform duration-500 hover:scale-105"
                    loading="lazy"
                  />
                </div>
              </a>
              <div class="p-5 flex flex-col flex-1">
                <a href="/article/{{ $related->slug }}" wire:navigate class="block flex-1">
                  <span class="text-xs text-accent font-semibold uppercase tracking-wider">
                    {{ $related->category }} • {{ \Carbon\Carbon::parse($related->published_date)->locale('id')->translatedFormat('d F Y') }}
                  </span>
                  <h3 class="font-semibold text-primary text-base leading-snug mt-2 hover:text-accent transition-colors line-clamp-2">{{ $related->title }}</h3>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</div>
