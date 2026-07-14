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
        return Product::with(['category', 'productable'])->get();
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
        $plant = $product->productable_type === \App\Models\PlantDetail::class ? $product->productable : null;

        return [
            $product->id,
            $product->name,
            $product->category->name ?? '',
            $plant?->species ?? '',
            $product->price,
            $plant?->care_level ?? '',
            $plant?->light ?? '',
            $plant?->watering ?? '',
            $plant?->pot_size ?? '',
            $product->short_description,
        ];
    }
}
