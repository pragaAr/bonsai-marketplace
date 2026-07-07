<?php

namespace App\Livewire\Admin\Access;

use App\Models\User as UserModel;
use App\Services\Admin\UserService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class User extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'role')]
    public string $filterRole = '';

    #[Url(as: 'isGoogle')]
    public ?string $isGoogleUser = null;

    public bool $isEditing = false;

    public bool $showFilterModal = false;

    public bool $showManageModal = false;

    public bool $showCreateModal = false;

    public ?int $selectedUserId = null;

    public string $selectedRole = '';

    public array $selectedPermissions = [];

    public string $name = '';

    public string $email = '';

    public ?string $whatsapp = null;

    public ?string $address = null;

    public string $password = '';

    public string $password_confirmation = '';

    public string $createRole = '';

    public ?int $deleteId = null;

    public bool $showDeleteModal = false;

    public function boot(UserService $service): void
    {
        $this->service = $service;
    }

    protected UserService $service;

    protected function rules(): array
    {
        return [
            'filterRole' => [
                'nullable',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
            'isGoogleUser' => ['nullable', 'in:0,1'],
            'selectedRole' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
                Rule::notIn(['system_admin']),
            ],
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'web')],
        ];
    }

    public function filterList(): void
    {
        $this->validate([
            'filterRole' => ['nullable', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'isGoogleUser' => ['nullable', 'in:0,1'],
        ]);

        $this->showFilterModal = false;
    }

    private function hasActiveFilter(): bool
    {
        return $this->filterRole !== '' || $this->isGoogleUser !== null;
    }

    public function resetFilters(): void
    {
        $this->reset(['filterRole', 'isGoogleUser']);
        $this->resetPage();
        $this->dispatch('filter-reset');
    }

    protected function getUserRules(?int $userId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                $userId ? Rule::unique('users', 'email')->ignore($userId) : 'unique:users,email',
            ],
            'whatsapp' => [
                'required',
                'string',
                'max:20',
                $userId ? Rule::unique('users', 'whatsapp')->ignore($userId) : 'unique:users,whatsapp',
            ],
            'address' => 'required|string',
            'createRole' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
                Rule::notIn(['system_admin', 'vendor']),
            ],
        ];

        if (! $userId || $this->password) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'whatsapp.unique' => 'Nomor WhatsApp sudah digunakan.',
            'address.required' => 'Alamat wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'createRole.required' => 'Anda harus memilih role untuk user ini.',
            'selectedRole.required' => 'Anda harus memilih role untuk user ini.',
        ];
    }

    public function openFilter(): void
    {
        $this->showFilterModal = true;
    }

    public function openCreate(): void
    {
        $this->isEditing = false;
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $user = UserModel::findOrFail($this->selectedUserId);

            $this->validate($this->getUserRules($user->id), $this->messages());

            $this->service->updateUser($user, [
                'name' => $this->name,
                'email' => $this->email,
                'whatsapp' => $this->whatsapp,
                'address' => $this->address,
                'role' => $this->createRole,
                'password' => $this->password ?: null,
            ]);

            $this->showCreateModal = false;
            $this->resetCreateForm();
            $this->dispatch('toast', message: 'User berhasil diperbarui.', type: 'success');
        } else {
            $this->validate($this->getUserRules(), $this->messages());

            $this->service->createUser([
                'name' => $this->name,
                'email' => $this->email,
                'whatsapp' => $this->whatsapp,
                'address' => $this->address,
                'password' => $this->password,
                'role' => $this->createRole,
            ]);

            $this->showCreateModal = false;
            $this->resetCreateForm();
            $this->dispatch('toast', message: 'User berhasil ditambahkan.', type: 'success');
        }
    }

    public function openEdit(int $id): void
    {
        $this->resetCreateForm();
        $user = UserModel::findOrFail($id);

        $this->selectedUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->whatsapp = $user->whatsapp;
        $this->address = $user->address;
        $this->createRole = $user->roles->pluck('name')->first() ?? '';

        $this->isEditing = true;
        $this->showCreateModal = true;
    }

    public function openAccess(int $id): void
    {
        $user = UserModel::with(['roles', 'permissions'])->findOrFail($id);

        if ($user->hasRole('system_admin')) {
            $this->dispatch('toast', message: 'Akun sistem tidak dapat dikelola dari daftar user.', type: 'error');

            return;
        }

        $this->selectedUserId = $user->id;
        $this->selectedRole = $user->roles->pluck('name')->first() ?? '';
        $this->selectedPermissions = $user->permissions->pluck('name')->all();
        $this->showManageModal = true;
    }

    public function saveAccess(): void
    {
        $this->validate();

        if (! $this->selectedUserId) {
            return;
        }

        $user = UserModel::findOrFail($this->selectedUserId);

        try {
            $this->service->saveAccess(
                user: $user,
                role: $this->selectedRole,
                permissions: $this->selectedPermissions,
                authId: auth()->id(),
            );
        } catch (\RuntimeException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');

            return;
        }

        $this->showManageModal = false;
        $this->selectedUserId = null;
        $this->selectedRole = '';
        $this->selectedPermissions = [];
        $this->dispatch('toast', message: 'Akses user berhasil diperbarui.', type: 'success');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRole(): void
    {
        $this->resetPage();
    }

    public function updatingIsGoogleUser(): void
    {
        $this->resetPage();
    }

    public function delete(): void
    {
        if (! $this->deleteId) {
            return;
        }

        $this->service->deleteUser($this->deleteId);

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'User berhasil dihapus.', type: 'success');
    }

    #[Layout('layouts.dashboard')]
    #[Title('Manajemen User')]
    public function render()
    {
        return view('livewire.admin.access.user', [
            'users' => $this->service->getUserList(
                search: $this->search,
                filterRole: $this->filterRole,
                isGoogleUser: $this->isGoogleUser,
            ),
            'allRoles' => Role::whereNotIn('name', ['system_admin'])->orderBy('name')->get(),
            'allPermissions' => Permission::orderBy('name')->get(),
            'selectedUser' => $this->selectedUserId ? UserModel::find($this->selectedUserId) : null,
            'title' => 'Manajemen User',
            'subTitle' => 'Kelola user dan hak akses',
            'hasActiveFilter' => $this->hasActiveFilter(),
        ]);
    }

    private function resetCreateForm(): void
    {
        $this->reset([
            'name',
            'email',
            'whatsapp',
            'password',
            'password_confirmation',
            'createRole',
        ]);
        $this->resetValidation();
    }

    private function resetFilterForm(): void
    {
        $this->reset([
            'filterRole',
            'isGoogleUser',
        ]);
        $this->resetValidation();
    }
}
