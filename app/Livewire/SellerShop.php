<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SellerShop extends Component
{
    public User $seller;

    public $products;

    public function mount(User $seller): void
    {
        $this->seller = $seller->load('sellerRequest');

        $this->products = Product::where('seller_id', $this->seller->id)
            ->where('status', 'approved')
            ->latest()
            ->get();
    }

    #[Layout('layouts.app')]
    #[Title('Toko Seller')]
    public function render()
    {
        return view('livewire.shop.seller-shop');
    }
}
