<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'short_description',
        'description',
        'category',
        'species',
        'care_level',
        'light',
        'watering',
        'pot_size',
        'featured',
        'seller_id',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'price' => 'integer',
        'seller_id' => 'integer',
        'status' => 'string',
        'approved_at' => 'datetime',
        'approved_by' => 'integer',
        'rejection_reason' => 'string',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('product');
    }

    /**
     * Get the product image URL with a fallback strategy.
     */
    /**
     * Relationship: product belongs to a seller (User).
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Relationship: admin who approved the product.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Accessor to check if product is approved.
     */
    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Register media collection with custom path and limits.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->onlyKeepLatest(4);
    }

    /**
     * Get the product image URL with a fallback strategy.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->hasMedia('images')) {
            $url = $this->getFirstMediaUrl('images');
            if (! empty($url)) {
                return $url;
            }
        }

        $slugToImageMap = [
            'ficus-ginseng-01' => 'bonsai-1.png',
            'japanese-maple-01' => 'bonsai-2.png',
            'juniper-cascade-01' => 'bonsai-3.png',
            'chinese-elm-01' => 'bonsai-4.png',
            'bougainvillea-01' => 'bonsai-5.png',
            'cherry-blossom-01' => 'bonsai-6.png',
            'jade-bonsai-01' => 'bonsai-1.png',
            'satsuki-azalea-01' => 'bonsai-6.png',
            'black-pine-01' => 'bonsai-3.png',
            'serissa-01' => 'bonsai-4.png',
            'hawaiian-umbrella-01' => 'bonsai-1.png',
            'brazilian-rain-01' => 'bonsai-5.png',
            'fukien-tea-01' => 'bonsai-2.png',
            'pomegranate-01' => 'bonsai-5.png',
            'trident-maple-01' => 'bonsai-2.png',
            'money-tree-01' => 'bonsai-4.png',
            'tiger-bark-ficus-01' => 'bonsai-3.png',
            'wisteria-01' => 'bonsai-6.png',
        ];

        $imageName = $slugToImageMap[$this->slug] ?? 'bonsai-1.png';

        return asset('images/'.$imageName);
    }
}
