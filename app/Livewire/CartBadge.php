<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartBadge extends Component
{
    #[On('cart-updated')]
    public function refresh()
    {
        // Re-renders the component when cart is updated
    }

    public function render()
    {
        $count = Auth::user()?->cart?->items()->sum('qty') ?? 0;

        return view('livewire.shop.cart-badge', [
            'count' => $count,
        ]);
    }
}
