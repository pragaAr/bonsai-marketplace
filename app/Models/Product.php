<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'stock',
        'short_description',
        'description',
        'category_id',
        'productable_id',
        'productable_type',
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
        'stock' => 'integer',
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
     * Relationship: product belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Relationship: polymorphic detail table (e.g. PlantDetail, PotDetail, etc.).
     */
    public function productable()
    {
        return $this->morphTo();
    }

    public function specifications(): array
    {
        return array_filter(
            $this->productable?->specifications() ?? [],
            fn ($value) => filled($value)
        );
    }

    public function isPlant(): bool
    {
        return $this->productable instanceof PlantDetail;
    }

    public function isPot(): bool
    {
        return $this->productable instanceof PotDetail;
    }

    public function isMedia(): bool
    {
        return $this->productable instanceof MediaDetail;
    }

    public function isFertilizer(): bool
    {
        return $this->productable instanceof FertilizerDetail;
    }

    public function isTool(): bool
    {
        return $this->productable instanceof ToolDetail;
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function availableStock(): int
    {
        return max(0, $this->stock ?? 0);
    }

    public function stockLabel(): string
    {
        if ($this->isAvailable()) {
            return 'Tersedia '.$this->availableStock();
        }

        return 'Habis';
    }

    /**
     * Relationship: product belongs to a seller (User).
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
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
     * Get the URL of the product's primary image.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('images');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        $term = trim($search);

        return $query->when($term !== '', function ($q) use ($term) {
            $likeTerm = "%{$term}%";

            $q->where(function ($innerQuery) use ($likeTerm) {
                $innerQuery->where('name', 'like', $likeTerm)
                    ->orWhere('short_description', 'like', $likeTerm)
                    ->orWhere('description', 'like', $likeTerm)
                    ->orWhereHasMorph('productable', [PlantDetail::class], function ($plantQuery) use ($likeTerm) {
                        $plantQuery->whereHas('species', function ($speciesQuery) use ($likeTerm) {
                            $speciesQuery->where('scientific_name', 'like', $likeTerm)
                                ->orWhere('common_name', 'like', $likeTerm);
                        });
                    });
            });
        });
    }

    public function scopeByCategory(Builder $query, string $categorySlug): Builder
    {
        return $query->when($categorySlug !== 'all', function ($q) use ($categorySlug) {
            $q->whereHas('category', fn ($catQuery) => $catQuery->where('slug', $categorySlug));
        });
    }

    public function scopeSortBy(Builder $query, string $sortOption, string $defaultSort = 'featured'): Builder
    {
        return match ($sortOption) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $defaultSort === 'latest'
                ? $query->latest()
                : $query->orderBy('featured', 'desc')->orderBy('id', 'desc'),
        };
    }
}
