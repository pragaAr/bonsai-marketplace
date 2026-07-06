<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'whatsapp',
        'address',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function getAvatarAttribute($value): string
    {
        // Prioritas 1: media kustom via Spatie Media Library
        // Hanya gunakan jika file fisiknya benar-benar ada di disk (cegah 404).
        if ($this->hasMedia('avatar')) {
            $media = $this->getFirstMedia('avatar');
            if ($media && $this->avatarFileExistsOnDisk($media)) {
                $url = $media->getUrl();
                if (filled($url)) {
                    return $url;
                }
            }
        }

        // Prioritas 2: URL non-Google yang disimpan langsung di kolom avatar.
        // URL Google (googleusercontent.com) sengaja dilewati karena sering berubah/expired.
        if (filled($value) && ! $this->isGoogleAvatarUrl($value)) {
            return $value;
        }

        // Prioritas 3: fallback UI Avatars
        return $this->fallbackAvatarUrl();
    }

    private function avatarFileExistsOnDisk(Media $media): bool
    {
        try {
            $disk = $media->disk ?: 'public';

            return Storage::disk($disk)->exists($media->getPathRelativeToRoot());
        } catch (\Throwable) {
            return false;
        }
    }

    public function fallbackAvatarUrl(): string
    {
        $name = urlencode($this->attributes['name'] ?? 'U');

        return "https://ui-avatars.com/api/?name={$name}&background=2D3E2F&color=F5F5F0&size=128";
    }

    public function isGoogleAvatarUrl(?string $url): bool
    {
        return filled($url) && str_contains($url, 'googleusercontent.com');
    }

    public function sellerRequest()
    {
        return $this->hasOne(SellerRequest::class);
    }

    public function getSellerStatusAttribute(): string
    {
        if ($this->hasRole('seller')) {
            return 'seller';
        }

        return $this->sellerRequest?->status ?? 'none';
    }

    public function getSellerLabelAttribute(): string
    {
        return match ($this->seller_status) {
            'seller' => 'Sudah menjadi penjual',
            'pending' => 'Pengajuan sedang ditinjau',
            'approved' => 'Pengajuan disetujui',
            'rejected' => 'Pengajuan ditolak',
            'banned' => 'Penjual dibekukan (Banned)',
            default => 'Belum menjadi penjual',
        };
    }
}
