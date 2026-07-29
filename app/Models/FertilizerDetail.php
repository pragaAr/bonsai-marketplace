<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertilizerDetail extends Model
{
    public $timestamps = false;

    protected $fillable = ['type', 'form', 'weight'];

    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }

    public function specifications(): array
    {
        return [
            'Tipe' => $this->type,
            'Formulasi' => $this->form,
            'Berat/Isi' => $this->weight,
        ];
    }
}
