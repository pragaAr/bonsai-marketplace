<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

class ProductDetail extends Component
{
    public $product;

    public $activeImageIndex = 0;

    public function mount($slug)
    {
        $this->product = Product::where('slug', $slug)->firstOrFail();

        // Log product view activity
        activity()
            ->performedOn($this->product)
            ->event('product_viewed')
            ->log("Viewed details of '{$this->product->name}'");
    }

    #[Title('Detail Produk')]
    public function render()
    {
        // Fetch 3 related specimens from the same category
        $relatedProducts = Product::where('category', $this->product->category)
            ->where('id', '!=', $this->product->id)
            ->take(3)
            ->get();

        return view('livewire.product-detail', [
            'relatedProducts' => $relatedProducts,
        ])->layout('layouts.app');
    }
}
