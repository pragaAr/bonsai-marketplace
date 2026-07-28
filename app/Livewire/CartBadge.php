<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartBadge extends Component
{
    #[On('cart-updated')]
    public function refresh()
    {
        // Re-renders the component when cart is updated
    }

    public function render()
    {
        $cart = session()->get('cart', []);
        $count = collect($cart)->sum('qty');

        return view('livewire.shop.cart-badge', [
            'count' => $count,
        ]);
    }
}
