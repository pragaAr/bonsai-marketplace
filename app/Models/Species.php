<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'scientific_name',
        'common_name',
        'slug',
    ];

    public function plantDetails()
    {
        return $this->hasMany(PlantDetail::class);
    }
}
