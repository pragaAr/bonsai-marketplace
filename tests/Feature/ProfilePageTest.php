<?php

namespace Tests\Feature;

use App\Livewire\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_user_sees_edit_and_seller_actions(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('Ubah data profil')
            ->assertSee('Ubah foto profil')
            ->assertSee('Jadi Penjual')
            ->assertSee('History pembelian');
    }

    public function test_google_only_user_sees_summary_without_edit_inputs(): void
    {
        $user = User::factory()->create([
            'name' => 'Google User',
            'email' => 'google@example.com',
            'google_id' => 'google-123',
            'avatar' => 'https://example.com/avatar.jpg',
            'password' => null,
        ]);

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('Google-only')
            ->assertDontSee('Ubah data profil')
            ->assertDontSee('Ubah foto profil');
    }

    public function test_manual_user_can_upload_profile_avatar_to_media_library(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->set('avatarFile', UploadedFile::fake()->image('avatar.png', 512, 512)->size(1024))
            ->call('saveAvatar')
            ->assertHasNoErrors();

        $media = $user->fresh()->getMedia('avatar')->first();

        $this->assertNotNull($media);
        $this->assertStringContainsString('user/profile', $media->getPath());
        $this->assertNotEmpty($user->fresh()->avatar);
    }
}
