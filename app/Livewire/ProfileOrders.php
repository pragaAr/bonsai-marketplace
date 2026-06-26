<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ProfileOrders extends Component
{
    #[Layout('layouts.app')]
    #[Title('History Pembelian')]
    public function render()
    {
        return view('livewire.profile-orders');
    }
}
