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

        if ($this->product->status !== 'approved') {
            if (! auth()->check() || (auth()->id() !== $this->product->seller_id && ! auth()->user()->hasAnyRole(['admin', 'system_admin']))) {
                abort(404);
            }
        }

        // Log product view activity
        activity()
            ->performedOn($this->product)
            ->event('product_viewed')
            ->log("Viewed details of '{$this->product->name}'");
    }

    #[Title('Detail Produk')]
    public function render()
    {
        // Fetch 3 related products from the same category
        $relatedProducts = Product::where('status', 'approved')
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->take(3)
            ->get();

        return view('livewire.product-detail', [
            'relatedProducts' => $relatedProducts,
        ])->layout('layouts.app');
    }
}
