<?php

namespace Tests\Unit\Services\Admin;

use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new UserService;

        // Seed role dasar yang dibutuhkan tes
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'system_admin', 'guard_name' => 'web']);
    }

    // -----------------------------------------------------------------------
    // getUserList
    // -----------------------------------------------------------------------

    #[Test]
    public function it_returns_paginated_users_excluding_system_admin(): void
    {
        $admin = User::factory()->create(['name' => 'Admin User']);
        $admin->syncRoles(['admin']);

        $systemAdmin = User::factory()->create(['name' => 'System Admin']);
        $systemAdmin->syncRoles(['system_admin']);

        $result = $this->service->getUserList();

        $this->assertCount(1, $result->items());
        $this->assertEquals($admin->id, $result->items()[0]->id);
    }

    #[Test]
    public function it_filters_users_by_search_term(): void
    {
        $userA = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.com']);
        $userA->syncRoles(['buyer']);

        $userB = User::factory()->create(['name' => 'Sari Dewi', 'email' => 'sari@example.com']);
        $userB->syncRoles(['buyer']);

        $result = $this->service->getUserList(search: 'Budi');

        $this->assertCount(1, $result->items());
        $this->assertEquals($userA->id, $result->items()[0]->id);
    }

    #[Test]
    public function it_filters_users_by_role(): void
    {
        $buyer = User::factory()->create();
        $buyer->syncRoles(['buyer']);

        $adminUser = User::factory()->create();
        $adminUser->syncRoles(['admin']);

        $result = $this->service->getUserList(filterRole: 'buyer');

        $this->assertCount(1, $result->items());
        $this->assertEquals($buyer->id, $result->items()[0]->id);
    }

    #[Test]
    public function it_filters_google_users(): void
    {
        $googleUser = User::factory()->create(['google_id' => 'google-123']);
        $googleUser->syncRoles(['buyer']);

        $regularUser = User::factory()->create(['google_id' => null]);
        $regularUser->syncRoles(['buyer']);

        $resultGoogle = $this->service->getUserList(isGoogleUser: '1');
        $this->assertCount(1, $resultGoogle->items());
        $this->assertEquals($googleUser->id, $resultGoogle->items()[0]->id);

        $resultNonGoogle = $this->service->getUserList(isGoogleUser: '0');
        $this->assertCount(1, $resultNonGoogle->items());
        $this->assertEquals($regularUser->id, $resultNonGoogle->items()[0]->id);
    }

    // -----------------------------------------------------------------------
    // createUser
    // -----------------------------------------------------------------------

    #[Test]
    public function it_creates_a_user_and_assigns_role(): void
    {
        $data = [
            'name'     => 'Pengguna Baru',
            'email'    => 'baru@example.com',
            'whatsapp' => '081234567890',
            'address'  => 'Jl. Contoh No. 1',
            'password' => 'password123',
            'role'     => 'buyer',
        ];

        $user = $this->service->createUser($data);

        $this->assertDatabaseHas('users', ['email' => 'baru@example.com']);
        $this->assertTrue($user->hasRole('buyer'));
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->google_id);
    }

    // -----------------------------------------------------------------------
    // updateUser
    // -----------------------------------------------------------------------

    #[Test]
    public function it_updates_user_data_and_role(): void
    {
        $user = User::factory()->create(['name' => 'Nama Lama']);
        $user->syncRoles(['buyer']);

        $updated = $this->service->updateUser($user, [
            'name'     => 'Nama Baru',
            'email'    => $user->email,
            'whatsapp' => $user->whatsapp,
            'address'  => $user->address,
            'role'     => 'admin',
        ]);

        $this->assertEquals('Nama Baru', $updated->name);
        $this->assertTrue($updated->hasRole('admin'));
        $this->assertFalse($updated->hasRole('buyer'));
    }

    #[Test]
    public function it_does_not_change_password_when_not_provided(): void
    {
        $user = User::factory()->create();
        $oldPasswordHash = $user->getAuthPassword();

        $this->service->updateUser($user, [
            'name'     => $user->name,
            'email'    => $user->email,
            'whatsapp' => $user->whatsapp,
            'address'  => $user->address,
            'role'     => 'buyer',
            'password' => null,
        ]);

        $this->assertEquals($oldPasswordHash, $user->fresh()->getAuthPassword());
    }

    // -----------------------------------------------------------------------
    // deleteUser
    // -----------------------------------------------------------------------

    #[Test]
    public function it_deletes_a_user(): void
    {
        $user = User::factory()->create();

        $this->service->deleteUser($user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function it_throws_exception_when_deleting_non_existent_user(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->service->deleteUser(99999);
    }

    // -----------------------------------------------------------------------
    // saveAccess
    // -----------------------------------------------------------------------

    #[Test]
    public function it_syncs_role_and_permissions(): void
    {
        $user = User::factory()->create();
        $user->syncRoles(['buyer']);

        $admin = User::factory()->create();
        $admin->syncRoles(['admin']);

        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit-products', 'guard_name' => 'web']);

        $this->service->saveAccess(
            user: $user,
            role: 'admin',
            permissions: ['edit-products'],
            authId: $admin->id,
        );

        $this->assertTrue($user->fresh()->hasRole('admin'));
        $this->assertTrue($user->fresh()->hasDirectPermission('edit-products'));
    }

    #[Test]
    public function it_throws_exception_when_saving_access_to_system_admin(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Akun sistem tidak dapat diubah dari daftar user.');

        $systemAdmin = User::factory()->create();
        $systemAdmin->syncRoles(['system_admin']);

        $this->service->saveAccess(
            user: $systemAdmin,
            role: 'admin',
            permissions: [],
            authId: 1,
        );
    }

    #[Test]
    public function it_throws_exception_when_admin_removes_own_access(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Anda tidak dapat menghapus akses admin dari akun sendiri.');

        $admin = User::factory()->create();
        $admin->syncRoles(['admin']);

        $this->service->saveAccess(
            user: $admin,
            role: 'buyer',          // downgrade diri sendiri
            permissions: [],
            authId: $admin->id,     // authId === user->id → ditolak
        );
    }
}
