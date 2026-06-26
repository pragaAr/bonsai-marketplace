<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-primary">Dashboard
      </h1>
      <p class="text-sm text-primary/60 mt-1">
        Selamat datang, {{ auth()->user()->name }}
      </p>
    </div>
  </div>

  <div
    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div
      class="bg-white rounded-xl shadow-sm border border-primary/10 p-5 hover:shadow-md transition-shadow">
      <div class="flex items-center gap-4">
        <div
          class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
          <svg class="w-6 h-6 text-primary"
            viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path
              d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
            <polyline
              points="3.27 6.96 12 12.01 20.73 6.96" />
            <line x1="12" y1="22.08"
              x2="12" y2="12" />
          </svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-primary">
            {{ \App\Models\Product::count() }}
          </p>
          <p class="text-xs text-primary/50">Total Produk
          </p>
        </div>
      </div>
    </div>

    <div
      class="bg-white rounded-xl shadow-sm border border-primary/10 p-5 hover:shadow-md transition-shadow">
      <div class="flex items-center gap-4">
        <div
          class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-amber-600"
            viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
          </svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-primary">
            {{ \App\Models\Product::where('status', 'pending')->count() }}
          </p>
          <p class="text-xs text-primary/50">Menunggu
            Persetujuan</p>
        </div>
      </div>
    </div>

    <div
      class="bg-white rounded-xl shadow-sm border border-primary/10 p-5 hover:shadow-md transition-shadow">
      <div class="flex items-center gap-4">
        <div
          class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-600"
            viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
          </svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-primary">
            {{ \App\Models\Product::where('status', 'approved')->count() }}
          </p>
          <p class="text-xs text-primary/50">Disetujui</p>
        </div>
      </div>
    </div>

    <div
      class="bg-white rounded-xl shadow-sm border border-primary/10 p-5 hover:shadow-md transition-shadow">
      <div class="flex items-center gap-4">
        <div
          class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600"
            viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path
              d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-primary">
            {{ \App\Models\User::count() }}
          </p>
          <p class="text-xs text-primary/50">Total Pengguna
          </p>
        </div>
      </div>
    </div>
  </div>

  <div
    class="bg-white rounded-xl shadow-sm border border-primary/10">
    <div class="p-5 border-b border-primary/10">
      <h2 class="text-lg font-semibold text-primary">Produk
        Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-primary/5">
          <tr>
            <th
              class="px-5 py-3 text-left text-xs font-medium text-primary/70 uppercase">
              Nama</th>
            <th
              class="px-5 py-3 text-left text-xs font-medium text-primary/70 uppercase">
              Kategori</th>
            <th
              class="px-5 py-3 text-left text-xs font-medium text-primary/70 uppercase">
              Harga</th>
            <th
              class="px-5 py-3 text-left text-xs font-medium text-primary/70 uppercase">
              Status</th>
            <th
              class="px-5 py-3 text-left text-xs font-medium text-primary/70 uppercase">
              Tanggal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-primary/10">
          @forelse(\App\Models\Product::latest()->take(5)->get() as $product)
            <tr
              class="hover:bg-primary/5 transition-colors">
              <td
                class="px-5 py-3 font-medium text-primary">
                {{ $product->name }}
              </td>
              <td class="px-5 py-3 text-primary/70">
                {{ $product->category }}
              </td>
              <td class="px-5 py-3 text-primary/70">
                Rp
                {{ number_format($product->price, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3">
                @if ($product->status === 'approved')
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Disetujui</span>
                @elseif($product->status === 'pending')
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                @else
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                @endif
              </td>
              <td class="px-5 py-3 text-primary/70">
                {{ $product->created_at->format('d M Y') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5"
                class="px-5 py-8 text-center text-primary/50">
                Belum ada produk</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
