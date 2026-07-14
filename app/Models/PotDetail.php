<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotDetail extends Model
{
    public $timestamps = false;
    protected $fillable = ['material', 'shape', 'dimensions', 'color'];

    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }
}
