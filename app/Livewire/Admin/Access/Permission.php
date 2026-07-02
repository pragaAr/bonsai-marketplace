<?php

namespace App\Livewire\Admin\Access;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission as PermissionModel;

class Permission extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public string $name = '';

    public string $label = '';

    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $editId = null;

    public bool $showDeleteModal = false;

    public ?int $deleteId = null;

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')
                    ->where('guard_name', 'web')
                    ->ignore($this->editId),
            ],
            'label' => 'nullable|string|max:255',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'label', 'editId', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $permission = PermissionModel::findOrFail($id);

        $this->editId = $permission->id;
        $this->isEditing = true;
        $this->name = $permission->name;
        $this->label = $permission->label ?? $this->labelFromName($permission->name);
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'name' => $this->name,
            'label' => filled($this->label) ? $this->label : $this->labelFromName($this->name),
        ];

        if ($this->isEditing && $this->editId) {
            PermissionModel::findOrFail($this->editId)->update($payload);
        } else {
            PermissionModel::create($payload + ['guard_name' => 'web']);
        }

        $this->dispatch('toast', message: $this->isEditing ? 'Permission berhasil diperbarui.' : 'Permission berhasil ditambahkan.', type: 'success');
        $this->showModal = false;
        $this->reset(['name', 'label', 'editId', 'isEditing']);
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

        $permission = PermissionModel::findOrFail($this->deleteId);

        if ($permission->roles()->exists() || $permission->users()->exists()) {
            $this->dispatch('toast', message: 'Permission masih dipakai role atau user.', type: 'error');

            return;
        }

        $permission->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Permission berhasil dihapus.', type: 'success');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.dashboard')]
    #[Title('Manajemen Permission')]
    public function render()
    {
        $query = PermissionModel::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('label', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.admin.access.permission', [
            'permissions' => $query->orderBy('name')->paginate(10),
            'title' => 'Manajemen Permission',
            'subTitle' => 'Kelola permission akses pengguna',
        ]);
    }

    private function labelFromName(string $name): string
    {
        return Str::headline(str_replace('.', ' ', $name));
    }
}
