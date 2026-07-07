<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Ambil daftar user dengan filter dan paginasi.
     * Tidak menyertakan system_admin.
     */
    public function getUserList(
        string $search = '',
        string $filterRole = '',
        ?string $isGoogleUser = null,
        int $perPage = 15,
    ): LengthAwarePaginator {
        $query = User::query()
            ->with(['roles', 'permissions'])
            ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'system_admin'));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        if ($filterRole) {
            $query->role($filterRole);
        }

        if ($isGoogleUser === '1') {
            $query->whereNotNull('google_id');
        } elseif ($isGoogleUser === '0') {
            $query->whereNull('google_id');
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Buat user baru dan langsung assign role-nya.
     *
     * @param  array{name: string, email: string, whatsapp: string, address: string, password: string, role: string}  $data
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'google_id'         => null,
                'name'              => $data['name'],
                'email'             => $data['email'],
                'whatsapp'          => $data['whatsapp'],
                'address'           => $data['address'],
                'email_verified_at' => now(),
                'avatar'            => null,
                'password'          => $data['password'],
            ]);

            $user->syncRoles([$data['role']]);

            return $user;
        });
    }

    /**
     * Perbarui data profil user yang sudah ada.
     * Password hanya diperbarui jika disertakan dalam $data.
     *
     * @param  array{name: string, email: string, whatsapp: string, address: string, role: string, password?: string}  $data
     */
    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'whatsapp' => $data['whatsapp'],
                'address'  => $data['address'],
            ]);

            if (! empty($data['password'])) {
                $user->update(['password' => $data['password']]);
            }

            $user->syncRoles([$data['role']]);

            return $user->fresh();
        });
    }

    /**
     * Hapus user berdasarkan ID.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    /**
     * Perbarui role dan permission (direct permission) milik user.
     * Mencegah penghapusan akses admin dari akun sendiri, dan melindungi akun system_admin.
     *
     * @param  array<string>  $permissions  Nama permission yang ingin di-sync
     *
     * @throws \RuntimeException
     */
    public function saveAccess(User $user, string $role, array $permissions, int $authId): void
    {
        if ($user->hasRole('system_admin')) {
            throw new \RuntimeException('Akun sistem tidak dapat diubah dari daftar user.');
        }

        if ($authId === $user->id && ! in_array($role, ['system_admin', 'admin'], true)) {
            throw new \RuntimeException('Anda tidak dapat menghapus akses admin dari akun sendiri.');
        }

        DB::transaction(function () use ($user, $role, $permissions) {
            $user->syncRoles([$role]);
            $user->syncPermissions($permissions);
        });
    }
}
