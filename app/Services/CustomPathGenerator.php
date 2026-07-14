<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    /**
     * Path untuk menyimpan berkas asli.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /**
     * Path untuk menyimpan berkas hasil konversi (misal thumbnail).
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /**
     * Path untuk menyimpan berkas responsif.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /**
     * Membuat base path terstruktur kustom.
     */
    protected function getBasePath(Media $media): string
    {
        $model = $media->model;
        $sellerId = $model->seller_id ?? 'unknown';
        $category = is_string($model->category) ? $model->category : ($model->category->name ?? 'uncategorized');

        // Ensure slug format for category
        $categorySlug = \Str::slug($category);

        return "product/{$sellerId}/{$categorySlug}/";
    }
}
