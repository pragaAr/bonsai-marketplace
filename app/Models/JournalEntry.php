<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class JournalEntry extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'author_id',
        'published_date',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];

    /**
     * Get the author of the journal entry.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the journal entry image URL with a fallback strategy.
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
            'journal-01' => 'journal-1.png',
            'journal-02' => 'bonsai-3.png',
            'journal-03' => 'bonsai-1.png',
            'journal-04' => 'bonsai-2.png',
        ];

        $imageName = $slugToImageMap[$this->slug] ?? 'journal-1.png';

        return asset('images/'.$imageName);
    }
}
