<div class="space-y-6">
  <!-- Header -->
  <div
    class="flex items-center justify-between border-b border-primary/10 pb-4">
    <div>
      <h1 class="text-2xl font-bold text-primary">
        {{ $isEditing ? 'Edit Produk' : 'Tambah Produk Baru' }}
      </h1>
      <p class="text-sm text-primary/60 mt-1">
        {{ $isEditing ? 'Perbarui data produk Anda' : 'Buat listing produk baru Anda untuk dipasarkan' }}
      </p>
    </div>
    <div>
      <a href="{{ route('seller.products') }}" wire:navigate
        class="inline-flex items-center justify-center px-4 py-2 bg-cream hover:bg-cream/80 text-primary border border-primary/10 font-medium rounded-lg text-sm transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4 mr-2" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round"
            stroke-linejoin="round" stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Daftar
      </a>
    </div>
  </div>

  <div
    class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <!-- Left Form Area -->
    <div class="lg:col-span-2 space-y-6">
      <!-- General Info Card -->
      <div
        class="bg-white rounded-xl shadow-sm border border-primary/10 p-6 space-y-4">
        <h2
          class="text-lg font-semibold text-primary border-b border-primary/5 pb-2">
          Informasi Umum</h2>

        <!-- Name -->
        <div class="space-y-1">
          <label for="name"
            class="block text-xs font-semibold text-primary/80 uppercase">Nama
            Produk <span
              class="text-red-500">*</span></label>
          <input type="text" id="name"
            wire:model="name"
            placeholder="Contoh: Bonsai Juniper Cascade Medium"
            class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
          @error('name')
            <span
              class="text-xs text-red-500 font-medium">{{ $message }}</span>
          @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Price -->
          <div class="space-y-1">
            <label for="price"
              class="block text-xs font-semibold text-primary/80 uppercase">Harga
              (Rp) <span
                class="text-red-500">*</span></label>
            <input type="number" id="price"
              wire:model="price"
              placeholder="Contoh: 1500000"
              class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
            @error('price')
              <span
                class="text-xs text-red-500 font-medium">{{ $message }}</span>
            @enderror
          </div>

          <!-- Stock -->
          <div class="space-y-1">
            <label for="stock"
              class="block text-xs font-semibold text-primary/80 uppercase">Stok
              Produk <span
                class="text-red-500">*</span></label>
            <input type="number" id="stock"
              wire:model="stock" placeholder="Contoh: 1"
              class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
            @error('stock')
              <span
                class="text-xs text-red-500 font-medium">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
          <!-- Category -->
          <div class="space-y-1">
            <label for="category_id"
              class="block text-xs font-semibold text-primary/80 uppercase">Kategori
              <span class="text-red-500">*</span></label>
            <div class="relative">
              <select id="category_id"
                wire:model.live="category_id"
                class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-white appearance-none">
                <option value="">Pilih Kategori
                </option>
                @foreach ($categories as $cat)
                  <option value="{{ $cat->id }}">
                    {{ $cat->name }}</option>
                @endforeach
              </select>
              <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-primary/50">
                <svg class="fill-current h-4 w-4"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20">
                  <path
                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
            @error('category_id')
              <span
                class="text-xs text-red-500 font-medium">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Short Description -->
        <div class="space-y-1">
          <label for="short_description"
            class="block text-xs font-semibold text-primary/80 uppercase">Deskripsi
            Singkat <span
              class="text-red-500">*</span></label>
          <input type="text" id="short_description"
            wire:model="short_description"
            placeholder="Contoh: Juniperus procumbens, gaya cascade pot 20cm"
            class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
          @error('short_description')
            <span
              class="text-xs text-red-500 font-medium">{{ $message }}</span>
          @enderror
        </div>

        <!-- Description -->
        <div class="space-y-1">
          <label for="description"
            class="block text-xs font-semibold text-primary/80 uppercase">Deskripsi
            Lengkap <span
              class="text-red-500">*</span></label>
          <textarea id="description" wire:model="description"
            rows="6"
            placeholder="Jelaskan kondisi tanaman, perawatan, asal, dan detail penting lainnya secara mendalam..."
            class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10"></textarea>
          @error('description')
            <span
              class="text-xs text-red-500 font-medium">{{ $message }}</span>
          @enderror
        </div>
      </div>

      <!-- Dynamic Specific Specs Card -->
      @if ($categorySlug)
        <div
          class="bg-white rounded-xl shadow-sm border border-primary/10 p-6 space-y-4">
          <h2
            class="text-lg font-semibold text-primary border-b border-primary/5 pb-2">
            Spesifikasi Detail
            ({{ $categories->firstWhere('slug', $categorySlug)->name }})
          </h2>

          <!-- TANAMAN (PLANT) -->
          @if ($categorySlug === 'tanaman')
            <div class="space-y-4">
              <div
                class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Species selection -->
                <div class="space-y-1">
                  <label for="species_id"
                    class="block text-xs font-semibold text-primary/80 uppercase">Spesies
                    Bonsai <span
                      class="text-red-500">*</span></label>
                  <div class="relative">
                    <select id="species_id"
                      wire:model="species_id"
                      class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-white appearance-none">
                      <option value="">Pilih Spesies
                      </option>
                      @foreach ($species as $sp)
                        <option
                          value="{{ $sp->id }}">
                          {{ $sp->scientific_name }}
                          ({{ $sp->common_name }})</option>
                      @endforeach
                    </select>
                    <div
                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-primary/50">
                      <svg class="fill-current h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path
                          d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                      </svg>
                    </div>
                  </div>
                  @error('species_id')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}</span>
                  @enderror
                </div>

                <!-- Care Level -->
                <div class="space-y-1">
                  <label for="care_level"
                    class="block text-xs font-semibold text-primary/80 uppercase">Tingkat
                    Perawatan <span
                      class="text-red-500">*</span></label>
                  <div class="relative">
                    <select id="care_level"
                      wire:model="care_level"
                      class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-white appearance-none">
                      <option value="Easy">Easy (Pemula)
                      </option>
                      <option value="Intermediate">
                        Intermediate (Sedang)</option>
                      <option value="Advanced">Advanced
                        (Mahir)</option>
                    </select>
                    <div
                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-primary/50">
                      <svg class="fill-current h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path
                          d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                      </svg>
                    </div>
                  </div>
                  @error('care_level')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}</span>
                  @enderror
                </div>
              </div>

              <!-- Or add new species scientific name -->
              <div
                class="bg-cream/10 border border-primary/10 rounded-lg p-4 space-y-3">
                <p
                  class="text-xs font-semibold text-primary/60 uppercase">
                  Atau Daftarkan Spesies Baru</p>
                <div
                  class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div class="space-y-1">
                    <label for="new_species_scientific"
                      class="block text-[10px] font-semibold text-primary/70 uppercase">Nama
                      Ilmiah / Scientific</label>
                    <input type="text"
                      id="new_species_scientific"
                      wire:model="new_species_scientific"
                      placeholder="Contoh: Premna microphylla"
                      class="w-full px-3 py-1.5 border border-primary/20 rounded-md text-xs text-primary focus:outline-none focus:border-accent bg-white" />
                    @error('new_species_scientific')
                      <span
                        class="text-xs text-red-500 font-medium">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="space-y-1">
                    <label for="new_species_common"
                      class="block text-[10px] font-semibold text-primary/70 uppercase">Nama
                      Umum / Common Name</label>
                    <input type="text"
                      id="new_species_common"
                      wire:model="new_species_common"
                      placeholder="Contoh: Sancang"
                      class="w-full px-3 py-1.5 border border-primary/20 rounded-md text-xs text-primary focus:outline-none focus:border-accent bg-white" />
                  </div>
                </div>
              </div>

              <div
                class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Light -->
                <div class="space-y-1">
                  <label for="light"
                    class="block text-xs font-semibold text-primary/80 uppercase">Pencahayaan
                    <span
                      class="text-red-500">*</span></label>
                  <input type="text" id="light"
                    wire:model="light"
                    placeholder="Contoh: Full sun / Panas penuh"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('light')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}</span>
                  @enderror
                </div>

                <!-- Watering -->
                <div class="space-y-1">
                  <label for="watering"
                    class="block text-xs font-semibold text-primary/80 uppercase">Penyiraman
                    <span
                      class="text-red-500">*</span></label>
                  <input type="text" id="watering"
                    wire:model="watering"
                    placeholder="Contoh: 2x sehari"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('watering')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}</span>
                  @enderror
                </div>

                <!-- Pot Size -->
                <div class="space-y-1">
                  <label for="pot_size"
                    class="block text-xs font-semibold text-primary/80 uppercase">Ukuran
                    Pot</label>
                  <input type="text" id="pot_size"
                    wire:model="pot_size"
                    placeholder="Contoh: 25 cm"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('pot_size')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>

            <!-- MEDIA TANAM (MEDIA) -->
          @elseif($categorySlug === 'media-tanam')
            <div
              class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="space-y-1">
                <label for="media_type"
                  class="block text-xs font-semibold text-primary/80 uppercase">Tipe
                  Media <span
                    class="text-red-500">*</span></label>
                <input type="text" id="media_type"
                  wire:model="media_type"
                  placeholder="Contoh: Pasir Malang"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('media_type')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
              <div class="space-y-1">
                <label for="media_weight"
                  class="block text-xs font-semibold text-primary/80 uppercase">Berat
                  (kg)</label>
                <input type="text" id="media_weight"
                  wire:model="media_weight"
                  placeholder="Contoh: 5 kg"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
              </div>
              <div class="space-y-1">
                <label for="media_volume"
                  class="block text-xs font-semibold text-primary/80 uppercase">Volume
                  (liter)</label>
                <input type="text" id="media_volume"
                  wire:model="media_volume"
                  placeholder="Contoh: 6 Liter"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
              </div>
            </div>

            <!-- POT -->
          @elseif($categorySlug === 'pot')
            <div
              class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1">
                <label for="pot_material"
                  class="block text-xs font-semibold text-primary/80 uppercase">Bahan
                  <span
                    class="text-red-500">*</span></label>
                <input type="text" id="pot_material"
                  wire:model="pot_material"
                  placeholder="Contoh: Keramik"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('pot_material')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
              <div class="space-y-1">
                <label for="pot_shape"
                  class="block text-xs font-semibold text-primary/80 uppercase">Bentuk
                  <span
                    class="text-red-500">*</span></label>
                <input type="text" id="pot_shape"
                  wire:model="pot_shape"
                  placeholder="Contoh: Oval"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('pot_shape')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
              <div class="space-y-1">
                <label for="pot_dimensions"
                  class="block text-xs font-semibold text-primary/80 uppercase">Dimensi
                  <span
                    class="text-red-500">*</span></label>
                <input type="text" id="pot_dimensions"
                  wire:model="pot_dimensions"
                  placeholder="Contoh: 25 x 18 x 7 cm"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('pot_dimensions')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
              <div class="space-y-1">
                <label for="pot_color"
                  class="block text-xs font-semibold text-primary/80 uppercase">Warna
                  <span
                    class="text-red-500">*</span></label>
                <input type="text" id="pot_color"
                  wire:model="pot_color"
                  placeholder="Contoh: Biru Glasir"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('pot_color')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <!-- PUPUK (FERTILIZER) -->
          @elseif($categorySlug === 'pupuk')
            <div
              class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="space-y-1">
                <label for="fertilizer_type"
                  class="block text-xs font-semibold text-primary/80 uppercase">Tipe
                  Pupuk <span
                    class="text-red-500">*</span></label>
                <input type="text" id="fertilizer_type"
                  wire:model="fertilizer_type"
                  placeholder="Contoh: Kimia NPK / Organik"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('fertilizer_type')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
              <div class="space-y-1">
                <label for="fertilizer_form"
                  class="block text-xs font-semibold text-primary/80 uppercase">Formulasi
                  <span
                    class="text-red-500">*</span></label>
                <input type="text" id="fertilizer_form"
                  wire:model="fertilizer_form"
                  placeholder="Contoh: Butiran Slow Release"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('fertilizer_form')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
              <div class="space-y-1">
                <label for="fertilizer_weight"
                  class="block text-xs font-semibold text-primary/80 uppercase">Berat/Isi
                  <span
                    class="text-red-500">*</span></label>
                <input type="text"
                  id="fertilizer_weight"
                  wire:model="fertilizer_weight"
                  placeholder="Contoh: 500 gram / 250 ml"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('fertilizer_weight')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <!-- ALAT (TOOL) -->
          @elseif($categorySlug === 'alat')
            <div
              class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="space-y-1">
                <label for="tool_material"
                  class="block text-xs font-semibold text-primary/80 uppercase">Bahan
                  <span
                    class="text-red-500">*</span></label>
                <input type="text" id="tool_material"
                  wire:model="tool_material"
                  placeholder="Contoh: Baja Hitam / Carbon Steel"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('tool_material')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
              <div class="space-y-1">
                <label for="tool_brand"
                  class="block text-xs font-semibold text-primary/80 uppercase">Merek
                  <span
                    class="text-red-500">*</span></label>
                <input type="text" id="tool_brand"
                  wire:model="tool_brand"
                  placeholder="Contoh: Ryuga / Lokal"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                @error('tool_brand')
                  <span
                    class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
              </div>
              <div class="space-y-1">
                <label for="tool_weight"
                  class="block text-xs font-semibold text-primary/80 uppercase">Berat
                  (gram)</label>
                <input type="text" id="tool_weight"
                  wire:model="tool_weight"
                  placeholder="Contoh: 300 gram"
                  class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
              </div>
            </div>
          @endif
        </div>
      @endif
    </div>

    <!-- Right Sidebar Area (Images & Tags & Submissions) -->
    <div class="space-y-6">
      <!-- Media/Images Upload Card -->
      <div
        class="bg-white rounded-xl shadow-sm border border-primary/10 p-6 space-y-4">
        <h2
          class="text-lg font-semibold text-primary border-b border-primary/5 pb-2">
          Gambar Produk (1 - 4) <span
            class="text-red-500">*</span></h2>

        <!-- Upload Zone -->
        <div class="space-y-3">
          <div
            class="relative flex flex-col items-center justify-center border-2 border-dashed border-primary/20 hover:border-accent rounded-xl p-4 bg-cream/5 cursor-pointer transition duration-150">
            <input type="file" wire:model="images"
              multiple accept="image/*"
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
            <svg xmlns="http://www.w3.org/2000/svg"
              class="h-8 w-8 text-primary/40 mb-2"
              fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p
              class="text-xs font-semibold text-primary/75">
              Klik atau Seret Gambar</p>
            <p class="text-[10px] text-primary/50 mt-1">
              PNG, JPG, JPEG, WEBP maksimal 2MB per file</p>
          </div>
          @error('images')
            <span
              class="text-xs text-red-500 font-medium block">{{ $message }}</span>
          @enderror
        </div>

        <!-- Preview Grid -->
        @if (count($existingImages) > 0 || count($images) > 0)
          <div class="grid grid-cols-2 gap-3 mt-4">
            <!-- Existing images from database -->
            @foreach ($existingImages as $img)
              <div
                class="relative w-full aspect-square rounded-lg border border-primary/10 overflow-hidden bg-cream group">
                <img src="{{ $img['url'] }}"
                  class="w-full h-full object-cover" />
                <button type="button"
                  wire:click="removeExistingImage({{ $img['id'] }})"
                  class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 shadow transition duration-150">
                  <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-3.5 w-3.5" fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            @endforeach

            <!-- Newly uploaded images preview -->
            @foreach ($images as $index => $img)
              @if ($img && method_exists($img, 'temporaryUrl'))
                <div
                  class="relative w-full aspect-square rounded-lg border border-primary/10 overflow-hidden bg-cream group">
                  <img src="{{ $img->temporaryUrl() }}"
                    class="w-full h-full object-cover" />
                  <button type="button"
                    wire:click="removeUploadImage({{ $index }})"
                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 shadow transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      class="h-3.5 w-3.5" fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              @endif
            @endforeach
          </div>
        @endif
      </div>

      <!-- Tags Selection Card -->
      @if ($category_id && count($availableTags) > 0)
        <div
          class="bg-white rounded-xl shadow-sm border border-primary/10 p-6 space-y-4">
          <h2
            class="text-lg font-semibold text-primary border-b border-primary/5 pb-2">
            Tags Produk</h2>
          <div class="flex flex-wrap gap-2">
            @foreach ($availableTags as $tag)
              <label
                class="inline-flex items-center cursor-pointer">
                <input type="checkbox"
                  value="{{ $tag->id }}"
                  wire:model="selectedTags"
                  class="sr-only peer" />
                <span
                  class="px-3 py-1.5 text-xs rounded-full border border-primary/20 text-primary bg-cream/10 peer-checked:bg-primary peer-checked:text-cream peer-checked:border-primary transition duration-150">
                  #{{ $tag->name }}
                </span>
              </label>
            @endforeach
          </div>
        </div>
      @endif

      <!-- Save Actions Card -->
      <div
        class="bg-white rounded-xl shadow-sm border border-primary/10 p-6 space-y-4">
        <h2
          class="text-lg font-semibold text-primary border-b border-primary/5 pb-2">
          Aksi Listing</h2>

        @if ($isEditing && $product->status === 'rejected')
          <div
            class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-800 leading-relaxed">
            <span class="font-bold">Info:</span> Sebelumnya
            ditolak dengan alasan: <br />
            <span
              class="italic font-medium">"{{ $product->rejection_reason }}"</span>.
            Perbaiki data produk lalu ajukan persetujuan
            kembali.
          </div>
        @endif

        <div class="flex flex-col gap-3">
          <!-- Submit for approval button -->
          <button type="button"
            wire:click="save('pending')"
            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-accent hover:bg-accent/95 text-white font-medium rounded-lg text-sm shadow transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg"
              class="h-4.5 w-4.5 mr-2" fill="none"
              viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2">
              <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Ajukan Persetujuan
          </button>

          <!-- Save as draft button -->
          <button type="button"
            wire:click="save('draft')"
            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white hover:bg-cream/40 text-primary border border-primary/20 font-medium rounded-lg text-sm transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg"
              class="h-4.5 w-4.5 mr-2" fill="none"
              viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2">
              <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Simpan sebagai Draft
          </button>

          <!-- Cancel button -->
          <a href="{{ route('seller.products') }}"
            wire:navigate
            class="w-full inline-flex items-center justify-center px-4 py-2 text-primary/60 hover:text-primary text-xs font-semibold text-center mt-2 transition duration-150">
            Batalkan Pengisian Form
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
