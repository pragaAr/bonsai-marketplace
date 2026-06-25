<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return Collection
     */
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category',
            'Species',
            'Price (IDR)',
            'Care Level',
            'Sunlight Needed',
            'Watering Frequency',
            'Pot Size',
            'Short Description',
        ];
    }

    /**
     * @var Product
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category,
            $product->species,
            $product->price,
            $product->care_level,
            $product->light,
            $product->watering,
            $product->pot_size,
            $product->short_description,
        ];
    }
}
