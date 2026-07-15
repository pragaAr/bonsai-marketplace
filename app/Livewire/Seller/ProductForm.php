<?php

namespace App\Livewire\Seller;

use App\Models\Category;
use App\Models\FertilizerDetail;
use App\Models\MediaDetail;
use App\Models\PlantDetail;
use App\Models\PotDetail;
use App\Models\Product;
use App\Models\Species;
use App\Models\Tag;
use App\Models\ToolDetail;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    public bool $isEditing = false;

    // General Form Fields
    public string $name = '';
    public ?int $price = null;
    public ?int $stock = 1;
    public string $short_description = '';
    public string $description = '';
    public ?int $category_id = null;

    // Selected Tags
    public array $selectedTags = [];

    // Image Uploads
    public $images = []; // TemporaryUploadedFile array
    public array $existingImages = []; // Array of arrays: ['id' => x, 'url' => y]
    public array $imagesToDelete = []; // Array of media ids to delete

    // Polymorphic details - PlantDetail
    public ?int $species_id = null;
    public string $new_species_scientific = '';
    public string $new_species_common = '';
    public string $care_level = 'Easy';
    public string $light = '';
    public string $watering = '';
    public string $pot_size = '';

    // Polymorphic details - MediaDetail
    public string $media_type = '';
    public string $media_weight = '';
    public string $media_volume = '';

    // Polymorphic details - PotDetail
    public string $pot_material = '';
    public string $pot_shape = '';
    public string $pot_dimensions = '';
    public string $pot_color = '';

    // Polymorphic details - FertilizerDetail
    public string $fertilizer_type = '';
    public string $fertilizer_form = '';
    public string $fertilizer_weight = '';

    // Polymorphic details - ToolDetail
    public string $tool_material = '';
    public string $tool_brand = '';
    public string $tool_weight = '';

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->isEditing = true;
            $this->product = Product::where('seller_id', auth()->id())
                ->with(['category', 'productable', 'tags'])
                ->findOrFail($id);

            // Populate general fields
            $this->name = $this->product->name;
            $this->price = $this->product->price;
            $this->stock = $this->product->stock;
            $this->short_description = $this->product->short_description;
            $this->description = $this->product->description;
            $this->category_id = $this->product->category_id;

            // Load tags
            $this->selectedTags = $this->product->tags->pluck('id')->toArray();

            // Load existing media
            $this->existingImages = $this->product->getMedia('images')->map(fn($media) => [
                'id' => $media->id,
                'url' => $media->getUrl()
            ])->toArray();

            // Populate polymorphic fields
            $this->loadPolymorphicDetails();
        } else {
            // Default category to the first one available
            $firstCategory = Category::first();
            if ($firstCategory) {
                $this->category_id = $firstCategory->id;
            }
        }
    }

    public function updatedCategoryId($value): void
    {
        // Reset selected tags and dynamic inputs when category changes
        $this->selectedTags = [];
        $this->resetPolymorphicFields();
    }

    private function resetPolymorphicFields(): void
    {
        $this->reset([
            'species_id', 'new_species_scientific', 'new_species_common', 'care_level', 'light', 'watering', 'pot_size',
            'media_type', 'media_weight', 'media_volume',
            'pot_material', 'pot_shape', 'pot_dimensions', 'pot_color',
            'fertilizer_type', 'fertilizer_form', 'fertilizer_weight',
            'tool_material', 'tool_brand', 'tool_weight'
        ]);
        $this->care_level = 'Easy';
    }

    private function loadPolymorphicDetails(): void
    {
        if (!$this->product || !$this->product->productable) {
            return;
        }

        $detail = $this->product->productable;

        if ($this->product->isPlant()) {
            $this->species_id = $detail->species_id;
            $this->care_level = $detail->care_level ?? 'Easy';
            $this->light = $detail->light ?? '';
            $this->watering = $detail->watering ?? '';
            $this->pot_size = $detail->pot_size ?? '';
        } elseif ($this->product->isMedia()) {
            $this->media_type = $detail->type ?? '';
            $this->media_weight = $detail->weight ?? '';
            $this->media_volume = $detail->volume ?? '';
        } elseif ($this->product->isPot()) {
            $this->pot_material = $detail->material ?? '';
            $this->pot_shape = $detail->shape ?? '';
            $this->pot_dimensions = $detail->dimensions ?? '';
            $this->pot_color = $detail->color ?? '';
        } elseif ($this->product->isFertilizer()) {
            $this->fertilizer_type = $detail->type ?? '';
            $this->fertilizer_form = $detail->form ?? '';
            $this->fertilizer_weight = $detail->weight ?? '';
        } elseif ($this->product->isTool()) {
            $this->tool_material = $detail->material ?? '';
            $this->tool_brand = $detail->brand ?? '';
            $this->tool_weight = $detail->weight ?? '';
        }
    }

    public function removeExistingImage(int $mediaId): void
    {
        $this->imagesToDelete[] = $mediaId;
        $this->existingImages = collect($this->existingImages)
            ->filter(fn($img) => $img['id'] !== $mediaId)
            ->toArray();
    }

    public function removeUploadImage(int $index): void
    {
        array_splice($this->images, $index, 1);
    }

    protected function getValidationRules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'short_description' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $category = Category::find($this->category_id);
        if ($category) {
            if ($category->slug === 'tanaman') {
                if (empty($this->new_species_scientific)) {
                    $rules['species_id'] = 'required|exists:species,id';
                } else {
                    $rules['new_species_scientific'] = 'string|max:255';
                    $rules['new_species_common'] = 'nullable|string|max:255';
                }
                $rules['care_level'] = 'required|string|max:255';
                $rules['light'] = 'required|string|max:255';
                $rules['watering'] = 'required|string|max:255';
                $rules['pot_size'] = 'nullable|string|max:255';
            } elseif ($category->slug === 'media-tanam') {
                $rules['media_type'] = 'required|string|max:255';
                $rules['media_weight'] = 'nullable|string|max:255';
                $rules['media_volume'] = 'nullable|string|max:255';
            } elseif ($category->slug === 'pot') {
                $rules['pot_material'] = 'required|string|max:255';
                $rules['pot_shape'] = 'required|string|max:255';
                $rules['pot_dimensions'] = 'required|string|max:255';
                $rules['pot_color'] = 'required|string|max:255';
            } elseif ($category->slug === 'pupuk') {
                $rules['fertilizer_type'] = 'required|string|max:255';
                $rules['fertilizer_form'] = 'required|string|max:255';
                $rules['fertilizer_weight'] = 'required|string|max:255';
            } elseif ($category->slug === 'alat') {
                $rules['tool_material'] = 'required|string|max:255';
                $rules['tool_brand'] = 'required|string|max:255';
                $rules['tool_weight'] = 'nullable|string|max:255';
            }
        }

        return $rules;
    }

    protected function getValidationMessages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.integer' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal 0.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'stock.min' => 'Stok minimal 0.',
            'short_description.required' => 'Deskripsi singkat wajib diisi.',
            'short_description.max' => 'Deskripsi singkat maksimal 255 karakter.',
            'description.required' => 'Deskripsi lengkap wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'species_id.required' => 'Spesies wajib dipilih.',
            'care_level.required' => 'Tingkat perawatan wajib diisi.',
            'light.required' => 'Kebutuhan cahaya wajib diisi.',
            'watering.required' => 'Kebutuhan air wajib diisi.',
            'media_type.required' => 'Tipe media tanam wajib diisi.',
            'pot_material.required' => 'Bahan pot wajib diisi.',
            'pot_shape.required' => 'Bentuk pot wajib diisi.',
            'pot_dimensions.required' => 'Dimensi pot wajib diisi.',
            'pot_color.required' => 'Warna pot wajib diisi.',
            'fertilizer_type.required' => 'Tipe pupuk wajib diisi.',
            'fertilizer_form.required' => 'Formulasi pupuk wajib diisi.',
            'fertilizer_weight.required' => 'Berat pupuk wajib diisi.',
            'tool_material.required' => 'Bahan alat wajib diisi.',
            'tool_brand.required' => 'Merek alat wajib diisi.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar hanya boleh jpeg, png, jpg, webp.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    public function save(string $statusType): void
    {
        // Tentukan status produk
        $status = $statusType === 'pending' ? 'pending' : 'draft';

        // Validasi umum & dynamic
        $this->validate($this->getValidationRules(), $this->getValidationMessages());

        // Validasi Jumlah Gambar (Total 1 - 4)
        $totalImages = count($this->existingImages) + count($this->images);
        if ($totalImages < 1) {
            $this->addError('images', 'Minimal harus mengunggah 1 gambar produk.');
            return;
        }
        if ($totalImages > 4) {
            $this->addError('images', 'Maksimal hanya boleh mengunggah 4 gambar produk.');
            return;
        }

        $category = Category::findOrFail($this->category_id);

        // 1. Tangani Spesies Baru jika diinput
        if ($category->slug === 'tanaman' && !empty($this->new_species_scientific)) {
            $species = Species::firstOrCreate(
                ['scientific_name' => $this->new_species_scientific],
                [
                    'common_name' => $this->new_species_common ?: null,
                    'slug' => Str::slug($this->new_species_scientific)
                ]
            );
            $this->species_id = $species->id;
        }

        // 2. Tangani Polymorphic Detail Model
        $productable = null;

        // Cek apakah kategori berubah (jika editing)
        $categoryChanged = false;
        if ($this->isEditing && $this->product) {
            $categoryChanged = ($this->product->category_id !== (int) $this->category_id);
            if ($categoryChanged && $this->product->productable) {
                $this->product->productable->delete();
            }
        }

        // Simpan / update data polymorphic
        if ($category->slug === 'tanaman') {
            $plantData = [
                'species_id' => $this->species_id,
                'care_level' => $this->care_level,
                'light' => $this->light,
                'watering' => $this->watering,
                'pot_size' => $this->pot_size ?: null,
            ];
            if ($this->isEditing && !$categoryChanged && $this->product->isPlant()) {
                $this->product->productable->update($plantData);
                $productable = $this->product->productable;
            } else {
                $productable = PlantDetail::create($plantData);
            }
        } elseif ($category->slug === 'media-tanam') {
            $mediaData = [
                'type' => $this->media_type,
                'weight' => $this->media_weight ?: null,
                'volume' => $this->media_volume ?: null,
            ];
            if ($this->isEditing && !$categoryChanged && $this->product->isMedia()) {
                $this->product->productable->update($mediaData);
                $productable = $this->product->productable;
            } else {
                $productable = MediaDetail::create($mediaData);
            }
        } elseif ($category->slug === 'pot') {
            $potData = [
                'material' => $this->pot_material,
                'shape' => $this->pot_shape,
                'dimensions' => $this->pot_dimensions,
                'color' => $this->pot_color,
            ];
            if ($this->isEditing && !$categoryChanged && $this->product->isPot()) {
                $this->product->productable->update($potData);
                $productable = $this->product->productable;
            } else {
                $productable = PotDetail::create($potData);
            }
        } elseif ($category->slug === 'pupuk') {
            $fertilizerData = [
                'type' => $this->fertilizer_type,
                'form' => $this->fertilizer_form,
                'weight' => $this->fertilizer_weight,
            ];
            if ($this->isEditing && !$categoryChanged && $this->product->isFertilizer()) {
                $this->product->productable->update($fertilizerData);
                $productable = $this->product->productable;
            } else {
                $productable = FertilizerDetail::create($fertilizerData);
            }
        } elseif ($category->slug === 'alat') {
            $toolData = [
                'material' => $this->tool_material,
                'brand' => $this->tool_brand,
                'weight' => $this->tool_weight ?: null,
            ];
            if ($this->isEditing && !$categoryChanged && $this->product->isTool()) {
                $this->product->productable->update($toolData);
                $productable = $this->product->productable;
            } else {
                $productable = ToolDetail::create($toolData);
            }
        }

        // 3. Simpan / Update Product
        $productData = [
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'productable_id' => $productable->id,
            'productable_type' => get_class($productable),
            'status' => $status,
        ];

        if ($this->isEditing && $this->product) {
            // Reset rejection reason if sending back for approval
            if ($status === 'pending') {
                $productData['rejection_reason'] = null;
            }

            $this->product->update($productData);
            $product = $this->product;

            // Log activity
            activity('product')
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->event('product_updated')
                ->log("Produk {$product->name} diperbarui oleh seller (Status: {$status})");
        } else {
            $productData['seller_id'] = auth()->id();
            $productData['slug'] = Str::slug($this->name) . '-' . time();
            $product = Product::create($productData);

            // Log activity
            activity('product')
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->event('product_created')
                ->log("Produk {$product->name} dibuat oleh seller (Status: {$status})");
        }

        // 4. Sync Tags
        $product->tags()->sync($this->selectedTags);

        // 5. Tangani Gambar Hapus
        foreach ($this->imagesToDelete as $mediaId) {
            $media = $product->getMedia('images')->firstWhere('id', $mediaId);
            if ($media) {
                $media->delete();
            }
        }

        // 6. Tangani Gambar Baru
        foreach ($this->images as $img) {
            $product->addMedia($img->getRealPath())
                ->usingFileName($img->hashName())
                ->toMediaCollection('images');
        }

        $message = $this->isEditing ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.';
        if ($status === 'pending') {
            $message .= ' Menunggu persetujuan admin.';
        }

        $this->dispatch('toast', message: $message, type: 'success');
        $this->redirect(route('seller.products'), navigate: true);
    }

    #[Layout('layouts.dashboard')]
    #[Title('Form Kelola Produk')]
    public function render()
    {
        $categories = Category::all();
        $species = Species::orderBy('scientific_name')->get();
        
        $availableTags = $this->category_id 
            ? Tag::where('category_id', $this->category_id)->get() 
            : collect();

        $selectedCategory = $this->category_id ? Category::find($this->category_id) : null;
        $categorySlug = $selectedCategory ? $selectedCategory->slug : '';

        return view('livewire.seller.product-form', [
            'categories' => $categories,
            'species' => $species,
            'availableTags' => $availableTags,
            'categorySlug' => $categorySlug,
        ]);
    }
}
