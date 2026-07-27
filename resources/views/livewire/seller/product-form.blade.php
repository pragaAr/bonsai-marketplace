<div class="space-y-6" x-data="{ currentStep: 1, lightboxImage: null }"
  @product-step-changed.window="currentStep = Number($event.detail.step)"
  @product-validation-failed.window="currentStep = Number($event.detail.step)"
  @keydown.escape.window="lightboxImage = null">
  <!-- Header -->
  <div
    class="flex items-center justify-between border-b border-primary/10 pb-4">
    <div>
      <h1 class="text-2xl font-bold text-primary">
        {{ $isEditing ? 'Edit Produk' : 'Tambah Produk' }}
      </h1>
      <p class="text-sm text-primary/60 mt-1">
        {{ $isEditing ? 'Perbarui data produk' : 'Tambah produk baru untuk dipasarkan' }}
      </p>
    </div>
    <div>
      <a href="{{ route('seller.products') }}" wire:navigate
        x-data="{ loading: false }" @click="loading = true"
        :class="loading ? 'opacity-80 pointer-events-none' : ''"
        class="w-full sm:w-auto px-3 py-2 bg-black/70 hover:bg-black/60 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition-smooth cursor-pointer inline-flex items-center justify-center gap-1 disabled:opacity-50 self-start sm:self-center">
        <x-icons.arrow-left x-show="!loading"
          class="w-4 h-4" />

        <x-icons.spinner x-show="loading" x-cloak
          class="h-4 w-4 text-current" />

        <span>Kembali</span>
      </a>
    </div>
  </div>

  <!-- Step indicator -->
  <div class="grid grid-cols-3 gap-2">
    <template x-for="step in [1, 2, 3]"
      :key="step">
      <div
        class="flex items-center gap-2 rounded-lg border p-3 transition"
        :class="currentStep === step ?
            'border-accent bg-accent/5 text-accent' :
            'border-primary/10 bg-white text-primary/45'">
        <span
          class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-xs font-bold"
          :class="currentStep === step ? 'bg-accent text-white' :
              'bg-primary/10'"
          x-text="step"></span>
        <span class="text-xs font-semibold sm:block"
          x-text="['Info Umum', 'Detail & Tags', 'Gambar & Aksi'][step - 1]"></span>
      </div>
    </template>
  </div>

  <div class="grid grid-cols-1 gap-6 items-start">
    <!-- Left Form Area -->
    <div class="lg:col-span-2 space-y-6"
      :class="currentStep === 3 ? 'hidden' : ''">
      <!-- General Info Card -->
      <div x-show="currentStep === 1" x-cloak
        class="bg-white rounded-xl shadow-sm border border-primary/10 p-6 space-y-4">
        <h2
          class="text-lg font-semibold text-primary border-b border-primary/5 pb-2">
          Informasi Umum
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1">
            <label for="category_id"
              class="block text-xs font-semibold text-primary/80 uppercase">
              Kategori
              <span class="text-red-500">*</span>
            </label>
            <div x-data="tomSelect({ value: @js((string) ($category_id ?? '')), placeholder: 'Pilih Kategori', ref: 'selectCategory' })" wire:ignore
              x-on:change.stop="$wire.set('category_id', $event.target.value)"
              class="w-full">
              <select id="category_id"
                x-ref="selectCategory" class="w-full">
                <option value="" disabled>Pilih
                  Kategori
                </option>
                @foreach ($categories as $cat)
                  <option value="{{ $cat->id }}">
                    {{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            @error('category_id')
              <span
                class="text-xs text-red-500 font-medium">{{ $message }}</span>
            @enderror
          </div>

          <div class="space-y-1">
            <label for="name"
              class="block text-xs font-semibold text-primary/80 uppercase">
              Nama Produk
              <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name"
              wire:model="name" placeholder="Nama produk.."
              class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10"
              autocomplete="off" />
            @error('name')
              <span
                class="text-xs text-red-500 font-medium">{{ $message }}
              </span>
            @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
          <div class="space-y-1">
            <label for="price"
              class="block text-xs font-semibold text-primary/80 uppercase">
              Harga (Rp)
              <span class="text-red-500">*</span>
            </label>
            <input type="text" id="price"
              x-data="priceFormatter()"
              x-init="initPrice()" x-model="displayPrice"
              @input="formatPrice()" @blur="cleanupPrice()"
              @keydown="onKeyDown($event)"
              placeholder="Harga.." maxlength="16"
              class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
            @error('price')
              <span class="text-xs text-red-500 font-medium">
                {{ $message }}
              </span>
            @enderror
          </div>

          <script>
            function priceFormatter() {
              return {
                displayPrice: '{{ $price ?? '' }}',

                initPrice() {
                  if (this.displayPrice) {
                    this.displayPrice = this.formatNumber(parseInt(
                      this.displayPrice) || 0);
                  }
                },

                formatPrice() {
                  // Remove all non-numeric characters
                  let numericValue = this.displayPrice.replace(/\D/g,
                    '');

                  // Limit to 12 digits
                  if (numericValue.length > 12) {
                    numericValue = numericValue.slice(0, 12);
                  }

                  // Prevent negative (already handled by removing non-numeric)
                  // Format with dots
                  this.displayPrice = this.formatNumber(numericValue);

                  // Update Livewire model with clean value
                  @this.set('price', numericValue ? parseInt(
                    numericValue) : null);
                },

                formatNumber(num) {
                  if (!num && num !== 0) return '';
                  return num.toString().replace(
                    /\B(?=(\d{3})+(?!\d))/g, '.');
                },

                onKeyDown(event) {
                  // Allow: backspace, delete, tab, escape, enter
                  if ([8, 9, 27, 13, 46].includes(event.keyCode)) {
                    return;
                  }

                  // Allow: Ctrl+C, Ctrl+V, Ctrl+X, Ctrl+A
                  if ((event.ctrlKey || event.metaKey) && [65, 67, 86,
                      88
                    ].includes(event.keyCode)) {
                    return;
                  }

                  // Ensure that it is a number and stop the keypress
                  if ((event.shiftKey || (event.keyCode < 48 || event
                      .keyCode > 57)) && (event.keyCode < 96 || event
                      .keyCode > 105)) {
                    event.preventDefault();
                  }
                },

                cleanupPrice() {
                  if (!this.displayPrice) {
                    @this.set('price', null);
                  }
                }
              }
            }
          </script>

          <div class="space-y-1">
            <label for="stock"
              class="block text-xs font-semibold text-primary/80 uppercase">
              Stok Produk
              <span class="text-red-500">*</span>
            </label>
            <input type="number" id="stock"
              wire:model="stock" placeholder="Contoh: 1"
              class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
            @error('stock')
              <span
                class="text-xs text-red-500 font-medium">{{ $message }}
              </span>
            @enderror
          </div>

          <div class="space-y-1 sm:col-span-2">
            <label for="short_description"
              class="block text-xs font-semibold text-primary/80 uppercase">
              Deskripsi Singkat
              <span class="text-red-500">*</span>
            </label>
            <input type="text" id="short_description"
              wire:model="short_description"
              placeholder="Contoh: Juniperus procumbens, gaya cascade pot 20cm"
              class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
            @error('short_description')
              <span
                class="text-xs text-red-500 font-medium">{{ $message }}
              </span>
            @enderror
          </div>
        </div>

        <div class="space-y-1">
          <label for="description"
            class="block text-xs font-semibold text-primary/80 uppercase">
            Deskripsi Lengkap
            <span class="text-red-500">*</span>
          </label>
          <textarea id="description" wire:model="description"
            rows="6"
            placeholder="Jelaskan kondisi tanaman, perawatan, asal, dan detail penting lainnya secara mendalam..."
            class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10"></textarea>
          @error('description')
            <span
              class="text-xs text-red-500 font-medium">{{ $message }}
            </span>
          @enderror
        </div>

        <div class="flex items-center justify-end pt-4">
          <button type="button" wire:click="nextStep(1)"
            wire:loading.attr="disabled"
            wire:target="nextStep(1)"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-4 py-2 text-sm font-medium text-white transition hover:bg-accent/95 cursor-pointer">
            Selanjutnya
            <x-icons.arrow-right aria-hidden="true"
              class="w-4 h-4" />
          </button>
        </div>
      </div>


      <div x-show="currentStep === 2" x-cloak>
        <div
          class="bg-white rounded-xl shadow-sm border border-primary/10 p-6 space-y-4">
          @if ($categorySlug)
            <h2
              class="text-lg font-semibold text-primary border-b border-primary/5 pb-2">
              Spesifikasi Detail
              ({{ $categories->firstWhere('slug', $categorySlug)->name }})
            </h2>

            @if ($categorySlug === 'tanaman')
              <div class="space-y-4">
                <div
                  class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="space-y-1">
                    <label for="species_id"
                      class="block text-xs font-semibold text-primary/80 uppercase">
                      Spesies Bonsai
                      <span class="text-red-500">*</span>
                    </label>
                    <div x-data="tomSelect({ value: @js((string) ($species_id ?? '')), placeholder: 'Pilih Spesies', ref: 'selectSpecies' })"
                      wire:ignore
                      x-on:change.stop="$wire.set('species_id', $event.target.value)"
                      class="w-full">
                      <select id="species_id"
                        x-ref="selectSpecies"
                        class="w-full">
                        <option value="" disabled>
                          Pilih
                          Spesies
                        </option>
                        @foreach ($species as $sp)
                          <option
                            value="{{ $sp->id }}">
                            {{ $sp->scientific_name }}
                            ({{ $sp->common_name }})
                          </option>
                        @endforeach
                      </select>
                    </div>
                    @error('species_id')
                      <span
                        class="text-xs text-red-500 font-medium">{{ $message }}
                      </span>
                    @enderror
                  </div>

                  <div class="space-y-1">
                    <label for="care_level"
                      class="block text-xs font-semibold text-primary/80 uppercase">
                      Tingkat Perawatan
                      <span class="text-red-500">*</span>
                    </label>
                    <div x-data="tomSelect({ value: @js((string) ($care_level ?? 'Easy')), placeholder: 'Pilih Tingkat Perawatan', ref: 'selectCareLevel' })"
                      wire:ignore
                      x-on:change.stop="$wire.set('care_level', $event.target.value)"
                      class="w-full">
                      <select id="care_level"
                        x-ref="selectCareLevel"
                        class="w-full">
                        <option value="Easy">Easy
                          (Pemula)
                        </option>
                        <option value="Intermediate">
                          Intermediate (Sedang)</option>
                        <option value="Advanced">Advanced
                          (Mahir)</option>
                      </select>
                    </div>
                    @error('care_level')
                      <span
                        class="text-xs text-red-500 font-medium">{{ $message }}
                      </span>
                    @enderror
                  </div>
                </div>

                <div
                  class="bg-cream/10 border border-primary/10 rounded-lg p-4 space-y-3">
                  <p
                    class="text-xs font-semibold text-primary/60 uppercase">
                    Atau Daftarkan Spesies Baru
                  </p>
                  <div
                    class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                      <label for="new_species_scientific"
                        class="block text-[10px] font-semibold text-primary/70 uppercase">
                        Nama Ilmiah
                      </label>
                      <input type="text"
                        id="new_species_scientific"
                        wire:model="new_species_scientific"
                        placeholder="Contoh: Premna microphylla"
                        class="w-full px-3 py-1.5 border border-primary/20 rounded-md text-xs text-primary focus:outline-none focus:border-accent bg-white" />
                      @error('new_species_scientific')
                        <span
                          class="text-xs text-red-500 font-medium">{{ $message }}
                        </span>
                      @enderror
                    </div>
                    <div class="space-y-1">
                      <label for="new_species_common"
                        class="block text-[10px] font-semibold text-primary/70 uppercase">
                        Nama Umum
                      </label>
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
                  <div class="space-y-1">
                    <label for="light"
                      class="block text-xs font-semibold text-primary/80 uppercase">
                      Pencahayaan
                      <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="light"
                      wire:model="light"
                      placeholder="Contoh: Full sun / Panas penuh"
                      class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                    @error('light')
                      <span
                        class="text-xs text-red-500 font-medium">{{ $message }}
                      </span>
                    @enderror
                  </div>

                  <div class="space-y-1">
                    <label for="watering"
                      class="block text-xs font-semibold text-primary/80 uppercase">
                      Penyiraman
                      <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="watering"
                      wire:model="watering"
                      placeholder="Contoh: 2x sehari"
                      class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                    @error('watering')
                      <span
                        class="text-xs text-red-500 font-medium">{{ $message }}
                      </span>
                    @enderror
                  </div>

                  <div class="space-y-1">
                    <label for="pot_size"
                      class="block text-xs font-semibold text-primary/80 uppercase">
                      Ukuran Pot
                    </label>
                    <input type="text" id="pot_size"
                      wire:model="pot_size"
                      placeholder="Contoh: 25 cm"
                      class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                    @error('pot_size')
                      <span
                        class="text-xs text-red-500 font-medium">{{ $message }}
                      </span>
                    @enderror
                  </div>
                </div>
              </div>
            @elseif($categorySlug === 'media-tanam')
              <div
                class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                  <label for="media_type"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Tipe Media
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="media_type"
                    wire:model="media_type"
                    placeholder="Contoh: Pasir Malang"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('media_type')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
                <div class="space-y-1">
                  <label for="media_weight"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Berat (kg)
                  </label>
                  <input type="text" id="media_weight"
                    wire:model="media_weight"
                    placeholder="Contoh: 5 kg"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                </div>
                <div class="space-y-1">
                  <label for="media_volume"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Volume (liter)
                  </label>
                  <input type="text" id="media_volume"
                    wire:model="media_volume"
                    placeholder="Contoh: 6 Liter"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                </div>
              </div>
            @elseif($categorySlug === 'pot')
              <div
                class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                  <label for="pot_material"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Bahan
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="pot_material"
                    wire:model="pot_material"
                    placeholder="Contoh: Keramik"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('pot_material')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
                <div class="space-y-1">
                  <label for="pot_shape"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Bentuk
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="pot_shape"
                    wire:model="pot_shape"
                    placeholder="Contoh: Oval"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('pot_shape')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
                <div class="space-y-1">
                  <label for="pot_dimensions"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Dimensi
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text"
                    id="pot_dimensions"
                    wire:model="pot_dimensions"
                    placeholder="Contoh: 25 x 18 x 7 cm"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('pot_dimensions')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
                <div class="space-y-1">
                  <label for="pot_color"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Warna
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="pot_color"
                    wire:model="pot_color"
                    placeholder="Contoh: Biru Glasir"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('pot_color')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
              </div>
            @elseif($categorySlug === 'pupuk')
              <div
                class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                  <label for="fertilizer_type"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Tipe Pupuk
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text"
                    id="fertilizer_type"
                    wire:model="fertilizer_type"
                    placeholder="Contoh: Kimia NPK / Organik"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('fertilizer_type')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
                <div class="space-y-1">
                  <label for="fertilizer_form"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Formulasi
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text"
                    id="fertilizer_form"
                    wire:model="fertilizer_form"
                    placeholder="Contoh: Butiran Slow Release"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('fertilizer_form')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
                <div class="space-y-1">
                  <label for="fertilizer_weight"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Berat/Isi
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text"
                    id="fertilizer_weight"
                    wire:model="fertilizer_weight"
                    placeholder="Contoh: 500 gram / 250 ml"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('fertilizer_weight')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
              </div>
            @elseif($categorySlug === 'alat')
              <div
                class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                  <label for="tool_material"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Bahan
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="tool_material"
                    wire:model="tool_material"
                    placeholder="Contoh: Baja Hitam / Carbon Steel"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('tool_material')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
                <div class="space-y-1">
                  <label for="tool_brand"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Merek
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="tool_brand"
                    wire:model="tool_brand"
                    placeholder="Contoh: Ryuga / Lokal"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                  @error('tool_brand')
                    <span
                      class="text-xs text-red-500 font-medium">{{ $message }}
                    </span>
                  @enderror
                </div>
                <div class="space-y-1">
                  <label for="tool_weight"
                    class="block text-xs font-semibold text-primary/80 uppercase">
                    Berat (gram)
                  </label>
                  <input type="text" id="tool_weight"
                    wire:model="tool_weight"
                    placeholder="Contoh: 300 gram"
                    class="w-full px-4 py-2 border border-primary/20 rounded-lg text-sm text-primary focus:outline-none focus:border-accent bg-cream/10" />
                </div>
              </div>
            @endif

            <!-- Tags Selection Card -->
            @if ($category_id && count($availableTags) > 0)
              <h2
                class="text-lg font-semibold text-primary border-b border-primary/5 pt-3 pb-2">
                Tags Produk
              </h2>
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
            @endif
          @else
            <div
              class="p-8 text-center text-sm text-primary/50">
              Pilih kategori terlebih dahulu untuk melihat
              spesifikasi detail.
            </div>
          @endif

          <div
            class="flex items-center justify-between pt-4">
            <button type="button"
              @click="currentStep = 1"
              class="inline-flex items-center justify-center gap-2 rounded-lg border border-primary/20 bg-white px-5 py-2.5 text-sm font-medium text-primary transition hover:bg-cream/40 cursor-pointer">
              <x-icons.arrow-left aria-hidden="true"
                class="w-4 h-4" />
              Kembali
            </button>

            <button type="button"
              wire:click="nextStep(2)"
              wire:loading.attr="disabled"
              wire:target="nextStep(2)"
              class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-2.5 text-sm font-medium text-white transition hover:bg-accent/95 cursor-pointer">
              Selanjutnya
              <x-icons.arrow-right aria-hidden="true"
                class="w-4 h-4" />
            </button>
          </div>
        </div>

      </div>
    </div>


    <div class="space-y-6"
      :class="currentStep === 3 ? 'lg:col-span-3' : ''">
      <!-- Media/Images Upload Card -->
      <div x-show="currentStep === 3" x-cloak
        class="bg-white rounded-xl shadow-sm border border-primary/10 p-6 space-y-4">
        <h2
          class="text-lg font-semibold text-primary border-b border-primary/5 pb-2">
          Gambar Produk (Maks 4)
          <span class="text-red-500">*</span>
        </h2>

        <!-- Upload Zone -->
        <div class="space-y-3">
          <div
            class="relative flex flex-col items-center justify-center border-2 border-dashed border-primary/20 hover:border-accent rounded-xl p-4 bg-cream/5 cursor-pointer transition duration-150">

            <input type="file" wire:model="images"
              multiple accept="image/*"
              wire:loading.attr="disabled"
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer disabled:cursor-not-allowed" />

            <div wire:loading.remove wire:target="images"
              class="w-full flex flex-col items-center justify-center text-center">
              <svg xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8 text-primary/40 mb-2 mx-auto"
                fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                  stroke-linejoin="round" stroke-width="2"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p
                class="text-xs font-semibold text-primary/75">
                Klik atau Seret Gambar
              </p>
              <p class="text-[10px] text-primary/50 mt-1">
                PNG, JPG, JPEG, WEBP maksimal 2MB per file
              </p>
            </div>

            <div wire:loading.flex wire:target="images"
              class="w-full flex flex-col items-center justify-center text-center">
              <x-icons.spinner
                class="h-7 w-7 text-accent mb-2" />

              <p
                class="text-xs font-semibold text-accent animate-pulse">
                Sedang mengupload gambar...
              </p>
            </div>
          </div>

          @error('images')
            <span
              class="text-xs text-center text-red-500 font-medium block">{{ $message }}
            </span>
          @enderror

          @foreach ($errors->get('images.*') as $message)
            <span
              class="text-xs text-center text-red-500 font-medium block">
              {{ $message }}
            </span>
          @endforeach
        </div>

        <!-- Preview Grid -->
        @if (count($existingImages) > 0 || count($images) > 0)
          <div
            class="flex flex-wrap items-center justify-center gap-3 mt-4">
            <!-- Existing images from database -->
            @foreach ($existingImages as $img)
              <div
                class="relative w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 aspect-square overflow-hidden rounded-lg border border-primary/10 bg-cream group shadow-sm">
                <button type="button"
                  class="block w-full h-full cursor-zoom-in"
                  @click="lightboxImage = @js($img['url'])"
                  aria-label="Lihat gambar dalam ukuran penuh">
                  <img src="{{ $img['url'] }}"
                    alt="Preview gambar produk"
                    class="w-full h-full object-cover transition duration-200 group-hover:scale-110" />
                  <span
                    class="absolute inset-0 flex items-center justify-center bg-primary/30 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                    aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 text-white drop-shadow"
                      fill="none" viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="1.8">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 15l5 5m-3-10a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m-3-3h6" />
                    </svg>
                  </span>
                </button>
                <button type="button"
                  wire:click="removeExistingImage({{ $img['id'] }})"
                  class="absolute top-1 right-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-white"
                  aria-label="Hapus gambar">
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
              @if ($img && method_exists($img, 'isPreviewable') && $img->isPreviewable())
                <div
                  class="relative w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 aspect-square overflow-hidden rounded-lg border border-primary/10 bg-cream group shadow-sm">
                  <button type="button"
                    class="block w-full h-full cursor-zoom-in"
                    @click="lightboxImage = @js($img->temporaryUrl())"
                    aria-label="Lihat gambar dalam ukuran penuh">
                    <img src="{{ $img->temporaryUrl() }}"
                      alt="Preview gambar baru"
                      class="w-full h-full object-cover transition duration-200 group-hover:scale-110" />
                    <span
                      class="absolute inset-0 flex items-center justify-center bg-primary/30 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                      aria-hidden="true">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-white drop-shadow"
                        fill="none" viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 15l5 5m-3-10a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m-3-3h6" />
                      </svg>
                    </span>
                  </button>
                  <button type="button"
                    wire:click="removeUploadImage({{ $index }})"
                    class="absolute top-1 right-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Hapus gambar">
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
              @elseif ($img)
                <div
                  class="relative w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 aspect-square overflow-hidden rounded-lg border border-red-200 bg-red-50 flex items-center justify-center p-2 text-center group shadow-sm">
                  <span class="text-[10px] text-red-600 font-medium leading-tight">File tidak valid</span>
                  <button type="button"
                    wire:click="removeUploadImage({{ $index }})"
                    class="absolute top-1 right-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Hapus gambar">
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

        <!-- Image lightbox -->
        <div x-show="lightboxImage" x-cloak
          x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0 scale-95"
          x-transition:enter-end="opacity-100 scale-100"
          x-transition:leave="transition ease-in duration-150"
          x-transition:leave-start="opacity-100 scale-100"
          x-transition:leave-end="opacity-0 scale-95"
          @click.self="lightboxImage = null"
          @keydown.escape.window="lightboxImage = null"
          class="fixed inset-0 z-50 flex h-[100dvh] w-screen items-center justify-center overflow-hidden bg-primary/80 backdrop-blur-sm p-4"
          role="dialog" aria-modal="true"
          aria-label="Preview gambar">
          <button type="button"
            @click="lightboxImage = null"
            class="absolute right-4 top-4 sm:right-6 sm:top-6 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white text-gray-900 shadow-xl transition-all duration-200 hover:bg-red-600 hover:text-white hover:scale-110 focus:outline-none focus:ring-2 focus:ring-white cursor-pointer"
            aria-label="Tutup preview gambar">
            <svg xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6" fill="none"
              viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2.5">
              <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          <img :src="lightboxImage"
            alt="Gambar produk ukuran penuh"
            class="max-h-[88dvh] max-w-[90vw] rounded-lg object-contain shadow-2xl" />
        </div>

        @if ($isEditing && $product->status === 'rejected')
          <div
            class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-800 leading-relaxed">
            <span class="font-bold">Info:</span>
            Sebelumnya ditolak dengan alasan: <br />
            <span
              class="italic font-medium">"{{ $product->rejection_reason }}"
            </span>.
            Perbaiki data produk lalu ajukan persetujuan
            kembali.
          </div>
        @endif

        <div class="grid grid-cols-3 gap-1.5 pt-4">
          <button type="button" @click="currentStep = 2"
            class="inline-flex w-full items-center justify-center gap-1 rounded-lg border border-primary/20 bg-white px-2 py-2 text-[11px] font-medium text-primary transition hover:bg-cream/40 sm:px-3 sm:text-sm cursor-pointer">
            <x-icons.arrow-left aria-hidden="true"
              class="w-4 h-4" />
            <span class="whitespace-nowrap">Kembali</span>
          </button>

          <button type="button"
            wire:click="save('draft')"
            wire:loading.attr="disabled"
            class="w-full inline-flex items-center justify-center gap-1 rounded-lg bg-slate-600 px-2 py-2 text-[11px] font-medium text-white transition hover:bg-slate-700 disabled:opacity-50 sm:px-3 sm:text-sm cursor-pointer">
            <x-icons.spinner wire:loading
              wire:target="save('draft')"
              class="h-3 w-3 text-current" />
            <span wire:loading.remove
              wire:target="save('draft')">
              <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
              </svg>
            </span>
            <span class="whitespace-nowrap">Draft</span>
          </button>

          <button type="button"
            wire:click="save('pending')"
            wire:loading.attr="disabled"
            class="w-full inline-flex items-center justify-center gap-1 rounded-lg bg-accent px-2 py-2 text-[11px] font-medium text-white transition-opacity hover:bg-accent/95 disabled:opacity-50 sm:px-3 sm:text-sm cursor-pointer">
            <x-icons.spinner wire:loading
              wire:target="save('pending')"
              class="h-3 w-3 text-current" />
            <span wire:loading.remove
              wire:target="save('pending')">
              <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </span>
            <span
              class="whitespace-nowrap">{{ $isEditing ? 'Perbaiki & Ajukan' : 'Ajukan' }}</span>
          </button>

        </div>
      </div>

    </div>
  </div>

  <!-- Success Modal (Only for Create Product) -->
  <div x-data="{ show: @entangle('showSuccessModal') }" x-show="show"
    x-transition.opacity.duration.300ms
    style="display: none;"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    x-effect="show ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')">

    <div x-show="show" x-trap="show"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-8"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 translate-y-8"
      class="bg-white rounded-2xl p-6 w-full max-w-md flex flex-col">

      <x-modal.header
        wire:click="$set('showSuccessModal', false)">
        Produk Berhasil Dibuat
      </x-modal.header>

      <div class="space-y-4 my-2 text-left">
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-emerald-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-xs sm:text-sm font-medium leading-relaxed">
            {{ $successMessage }}
          </p>
        </div>

        <div class="flex gap-3 pt-4">
          <a href="{{ route('seller.products') }}" wire:navigate
            class="flex-1 px-4 py-2.5 bg-primary text-white text-center font-semibold text-xs sm:text-sm rounded-xl hover:shadow-lg transition-smooth cursor-pointer inline-flex items-center justify-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <span>Daftar Produk</span>
          </a>

          <button type="button"
            wire:click="resetFormAndCreateAnother"
            class="flex-1 px-4 py-2.5 bg-cream border border-primary/20 text-primary text-center font-semibold text-xs sm:text-sm rounded-xl hover:bg-primary/10 transition-smooth cursor-pointer inline-flex items-center justify-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Buat Produk Lagi</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
