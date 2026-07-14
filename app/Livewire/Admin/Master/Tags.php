<?php

namespace App\Livewire\Admin\Master;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Tags extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public string $name = '';

    public string $slug = '';

    public ?int $categoryId = null;

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
                Rule::unique('tags', 'slug')->ignore($this->editId),
            ],
            'categoryId' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'slug', 'categoryId', 'description', 'editId', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $tag = Tag::findOrFail($id);

        $this->editId = $tag->id;
        $this->isEditing = true;
        $this->name = $tag->name;
        $this->slug = $tag->slug;
        $this->categoryId = $tag->category_id;
        $this->description = $tag->description ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'category_id' => $this->categoryId,
            'description' => $this->description,
        ];

        if ($this->isEditing && $this->editId) {
            Tag::findOrFail($this->editId)->update($payload);
        } else {
            Tag::create($payload);
        }

        $this->dispatch('toast', message: $this->isEditing ? 'Tag berhasil diperbarui.' : 'Tag berhasil ditambahkan.', type: 'success');
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

        Tag::findOrFail($this->deleteId)->delete();
        $this->deleteId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', message: 'Tag berhasil dihapus.', type: 'success');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.dashboard')]
    #[Title('Manajemen Tag')]
    public function render()
    {
        $query = Tag::with('category');

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            });
        }

        return view('livewire.admin.master.tags', [
            'tags' => $query->orderBy('name')->paginate(10),
            'categories' => Category::orderBy('name')->get(),
            'title' => 'Tags',
            'subTitle' => 'Kelola tag produk untuk marketplace',
        ]);
    }
}
