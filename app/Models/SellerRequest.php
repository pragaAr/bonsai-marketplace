<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SellerRequest extends Model
{
    use HasFactory;

    protected function storeName(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): array => [
                'store_name' => $value,
                'store_slug' => Str::slug($value),
            ],
        );
    }

    protected $fillable = [
        'user_id',
        'store_name',
        'store_slug',
        'owner_name',
        'city_name',
        'province_name',
        'agreement',
        'whatsapp',
        'notes',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
