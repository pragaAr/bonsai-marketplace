<?php

namespace App\Livewire\Seller;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('layouts.dashboard')]
    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.seller.dashboard', [
            'title' => 'Dashboard',
            'subTitle' => 'Selamat datang, '.auth()->user()->name,
        ]);
    }
}
