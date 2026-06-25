<?php

namespace Tests\Feature;

use App\Livewire\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile_page(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_profile_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->set('name', 'Nama Baru')
            ->set('email', 'baru@example.com')
            ->set('avatar', 'https://example.com/avatar.jpg')
            ->set('current_password', 'password123')
            ->set('password', 'password456')
            ->set('password_confirmation', 'password456')
            ->call('saveProfile')
            ->assertHasNoErrors();

        $freshUser = $user->fresh();

        $this->assertSame('Nama Baru', $freshUser->name);
        $this->assertSame('baru@example.com', $freshUser->email);
        $this->assertSame('https://example.com/avatar.jpg', $freshUser->avatar);
        $this->assertTrue(Hash::check('password456', $freshUser->password));
    }
}
