<div class="pt-24 pb-16 sm:pt-28 sm:pb-20">
  <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-primary/10 bg-white/85 p-6 shadow-sm backdrop-blur sm:p-8">
      <p class="text-xs font-semibold uppercase tracking-[0.3em] text-accent">Akun</p>
      <h1 class="mt-3 text-3xl font-semibold tracking-tight text-primary sm:text-4xl">History Pembelian</h1>
      <p class="mt-4 max-w-2xl text-sm leading-6 text-primary/70 sm:text-base">
        Halaman ini disiapkan untuk riwayat pesanan. Saat persistence order sudah aktif, daftar transaksi dan detailnya akan muncul di sini.
      </p>

      <div class="mt-8 rounded-3xl border border-dashed border-primary/15 bg-cream p-6 sm:p-8">
        <h2 class="text-lg font-semibold text-primary">Belum ada data order</h2>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-primary/65">
          Checkout saat ini masih berjalan sebagai mock, jadi history pembelian belum bisa diisi dari database.
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
          <a href="{{ route('shop') }}" wire:navigate class="inline-flex items-center justify-center rounded-full bg-primary px-5 py-3 text-sm font-semibold text-cream transition-colors hover:bg-primary/90">
            Lanjut belanja
          </a>
          <a href="{{ route('profile') }}" wire:navigate class="inline-flex items-center justify-center rounded-full border border-primary/15 bg-white px-5 py-3 text-sm font-semibold text-primary transition-colors hover:bg-primary/5">
            Kembali ke profil
          </a>
        </div>
      </div>
    </div>
  </div>
</div>