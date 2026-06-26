<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class LandingPage extends Component
{
    #[Layout('layouts.app')]
    #[Title('')]
    public function render()
    {
        $featuredProducts = Product::where('featured', true)->take(6)->get();

        return view('livewire.landing-page.index', compact('featuredProducts'));
    }
}
