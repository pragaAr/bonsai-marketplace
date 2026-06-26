<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GoogleAvatarCache
{
    private const MIME_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    /**
     * Download dan simpan avatar Google ke Spatie Media Library.
     *
     * Strategi:
     * - Jika user sudah punya media avatar DAN file fisik ada di disk → skip (cache valid).
     * - Jika user punya media record tapi file fisik tidak ada (storage reset/hilang) → re-download.
     * - Jika $replaceExisting = true → hapus media lama dan download ulang.
     */
    public function cache(User $user, ?string $avatarUrl, bool $replaceExisting = false): bool
    {
        if (blank($avatarUrl)) {
            return false;
        }

        if (! Str::contains($avatarUrl, 'googleusercontent.com')) {
            return false;
        }

        // Jika sudah ada media dan tidak dipaksa replace, cek dulu apakah file fisik ada.
        // Jika file fisik masih ada → cache valid, tidak perlu download.
        // Jika file fisik hilang → lanjutkan re-download meskipun replaceExisting = false.
        if (! $replaceExisting && $user->hasMedia('avatar')) {
            $media = $user->getFirstMedia('avatar');
            if ($media && $this->fileExistsOnDisk($media)) {
                return true;
            }

            // File fisik hilang — hapus record lama dan re-download
            Log::info("Avatar file hilang di disk untuk user {$user->id}, akan re-cache dari Google.");
            $user->clearMediaCollection('avatar');
        }

        // Normalisasi URL Google: ganti parameter ukuran ke s256 agar resolusi lebih baik.
        $normalizedUrl = $this->normalizeGoogleAvatarUrl($avatarUrl);

        $temporaryPath = null;

        try {
            $response = Http::timeout(10)
                ->retry(2, 300)
                ->withHeaders([
                    'User-Agent' => config('app.name', 'Laravel').' avatar-cache',
                ])
                ->get($normalizedUrl);

            if (! $response->successful()) {
                Log::warning("Gagal download Google avatar untuk user {$user->id}: HTTP {$response->status()} dari URL: {$normalizedUrl}");

                return false;
            }

            $mime = Str::before($response->header('Content-Type', 'image/jpeg'), ';');
            $mime = trim($mime);
            $extension = self::MIME_EXTENSIONS[$mime] ?? null;

            if ($extension === null) {
                // Fallback: coba tebak dari Content-Type
                Log::warning("Google avatar user {$user->id} memakai tipe file tidak dikenal: '{$mime}', fallback ke jpg.");
                $extension = 'jpg';
            }

            $temporaryPath = storage_path('app/livewire-tmp/google-avatar-'.$user->id.'-'.Str::random(8).'.'.$extension);
            File::ensureDirectoryExists(dirname($temporaryPath));
            file_put_contents($temporaryPath, $response->body());

            if ($replaceExisting) {
                $user->clearMediaCollection('avatar');
            }

            $user->addMedia($temporaryPath)
                ->usingFileName('google-avatar-'.$user->id.'.'.$extension)
                ->withCustomProperties(['source' => 'google'])
                ->toMediaCollection('avatar', 'public');

            return true;
        } catch (\Throwable $e) {
            Log::warning("Gagal cache Google avatar untuk user {$user->id}: ".$e->getMessage());

            return false;
        } finally {
            if ($temporaryPath && file_exists($temporaryPath)) {
                @unlink($temporaryPath);
            }
        }
    }

    /**
     * Normalisasi URL Google avatar: hapus atau ganti parameter ukuran bawaan
     * yang sering menyebabkan 404 (misalnya =s96-c) dengan ukuran lebih besar.
     */
    public function normalizeGoogleAvatarUrl(string $url): string
    {
        // Hapus parameter lama seperti =s96-c atau =s96 di akhir URL
        $url = preg_replace('/=s\d+(-c)?$/', '', $url);

        return rtrim($url, '=');
    }

    /**
     * Verifikasi apakah file fisik dari media record benar-benar ada di disk.
     */
    private function fileExistsOnDisk(Media $media): bool
    {
        try {
            $disk = $media->disk ?: 'public';

            return Storage::disk($disk)->exists($media->getPathRelativeToRoot());
        } catch (\Throwable) {
            return false;
        }
    }
}
