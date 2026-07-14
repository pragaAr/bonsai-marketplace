<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolDetail extends Model
{
    public $timestamps = false;
    protected $fillable = ['material', 'brand', 'weight'];

    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }
}
