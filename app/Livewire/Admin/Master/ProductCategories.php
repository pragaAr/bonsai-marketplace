<?php

namespace App\Livewire\Admin\Master;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCategories extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $editId = null;

    public bool $showDeleteModal = false;

    public ?int $deleteId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->editId),
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'slug', 'description', 'editId', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->editId = $category->id;
        $this->isEditing = true;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'description' => $this->description,
        ];

        if ($this->isEditing && $this->editId) {
            Category::findOrFail($this->editId)->update($payload);
        } else {
            Category::create($payload);
        }

        $this->dispatch('toast', message: $this->isEditing ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.', type: 'success');
        $this->showModal = false;
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (! $this->deleteId) {
            return;
        }

        Category::findOrFail($this->deleteId)->delete();
        $this->deleteId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', message: 'Kategori berhasil dihapus.', type: 'success');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.dashboard')]
    #[Title('Manajemen Kategori Produk')]
    public function render()
    {
        $query = Category::query();

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.admin.master.product-categories', [
            'categories' => $query->orderBy('name')->paginate(10),
            'title' => 'Kategori Produk',
            'subTitle' => 'Kelola kategori produk untuk marketplace',
        ]);
    }
}
