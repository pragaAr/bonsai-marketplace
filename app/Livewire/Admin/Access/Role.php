<?php

namespace App\Livewire\Admin\Access;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as RoleModel;

class Role extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public string $name = '';

    public array $selectedPermissions = [];

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
                Rule::unique('roles', 'name')
                    ->where('guard_name', 'web')
                    ->ignore($this->editId),
            ],
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'string|exists:permissions,name',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'selectedPermissions', 'editId', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $role = RoleModel::with('permissions')->findOrFail($id);

        if ($role->name === 'system_admin') {
            $this->dispatch('toast', message: 'Role sistem tidak dapat dikelola dari daftar role.', type: 'error');

            return;
        }

        $this->editId = $role->id;
        $this->isEditing = true;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->all();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing && $this->editId) {
            $role = RoleModel::findOrFail($this->editId);
            $role->update(['name' => $this->name]);
        } else {
            $role = RoleModel::create(['name' => $this->name, 'guard_name' => 'web']);
        }

        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('toast', message: $this->isEditing ? 'Role berhasil diperbarui.' : 'Role berhasil ditambahkan.', type: 'success');
        $this->showModal = false;
        $this->reset(['name', 'selectedPermissions', 'editId', 'isEditing']);
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

        $role = RoleModel::findOrFail($this->deleteId);

        if (in_array($role->name, ['system_admin', 'admin', 'seller', 'user'], true)) {
            $this->dispatch('toast', message: 'Role sistem tidak dapat dihapus.', type: 'error');

            return;
        }

        if ($role->users()->exists()) {
            $this->dispatch('toast', message: 'Role masih dipakai user.', type: 'error');

            return;
        }

        $role->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Role berhasil dihapus.', type: 'success');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.dashboard')]
    #[Title('Manajemen Role')]
    public function render()
    {
        $query = RoleModel::query()
            ->where('name', '!=', 'system_admin')
            ->with(['permissions' => fn ($permissionQuery) => $permissionQuery->orderBy('name')])
            ->withCount('users');

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        return view('livewire.admin.access.role', [
            'roles' => $query->orderBy('name')->paginate(10),
            'allPermissions' => Permission::orderBy('name')->get(),
            'title' => 'Manajemen Role',
            'subTitle' => 'Kelola role akses pengguna',
        ]);
    }
}
