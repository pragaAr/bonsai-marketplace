<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaDetail extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'weight', 'volume'];

    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }

    public function specifications(): array
    {
        return [
            'Tipe' => $this->type,
            'Berat' => $this->weight,
            'Volume' => $this->volume,
        ];
    }
}
