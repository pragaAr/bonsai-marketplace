<?php

namespace App\Livewire\Admin\Master;

use App\Models\Species as SpeciesModel;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Species extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public string $scientific_name = '';

    public ?string $common_name = null;

    public string $slug = '';

    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $editId = null;

    public bool $showDeleteModal = false;

    public ?int $deleteId = null;

    protected function rules(): array
    {
        return [
            'scientific_name' => [
                'required',
                Rule::unique('species', 'scientific_name')->ignore($this->editId),
            ],
            'common_name' => 'nullable|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('species', 'slug')->ignore($this->editId),
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'scientific_name.required' => 'Nama ilmiah wajib diisi.',
            'scientific_name.unique' => 'Nama ilmiah sudah digunakan.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan.',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['scientific_name', 'common_name', 'slug', 'editId', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $species = SpeciesModel::findOrFail($id);

        $this->editId = $species->id;
        $this->isEditing = true;
        $this->scientific_name = $species->scientific_name;
        $this->common_name = $species->common_name;
        $this->slug = $species->slug;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'scientific_name' => $this->scientific_name,
            'common_name' => $this->common_name,
            'slug' => Str::slug($this->slug),
        ];

        if ($this->isEditing && $this->editId) {
            SpeciesModel::findOrFail($this->editId)->update($payload);
        } else {
            SpeciesModel::create($payload);
        }

        $this->dispatch('toast', message: $this->isEditing ? 'Spesies berhasil diperbarui.' : 'Spesies berhasil ditambahkan.', type: 'success');
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

        SpeciesModel::findOrFail($this->deleteId)->delete();
        $this->deleteId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', message: 'Spesies berhasil dihapus.', type: 'success');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.dashboard')]
    #[Title('Manajemen Spesies')]
    public function render()
    {
        $query = SpeciesModel::query();

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('scientific_name', 'like', "%{$this->search}%")
                    ->orWhere('common_name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.admin.master.species', [
            'species' => $query->orderBy('scientific_name')->paginate(10),
            'title' => 'Spesies',
            'subTitle' => 'Kelola spesies tanaman bonsai.',
        ]);
    }
}
