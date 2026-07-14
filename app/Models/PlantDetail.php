<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantDetail extends Model
{
    public $timestamps = false;
    protected $fillable = ['species_id', 'care_level', 'light', 'watering', 'pot_size'];

    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }

    public function species()
    {
        return $this->belongsTo(Species::class);
    }
}
