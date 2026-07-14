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
}
