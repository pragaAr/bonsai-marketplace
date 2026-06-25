<?php

namespace App\Livewire\Seller;

use Livewire\Component;

class Dashboard extends Component
{
  public function render()
  {
    return view('livewire.seller.dashboard')
      ->layout('layouts.dashboard', ['title' => 'Seller Dashboard - bonsaiku']);
  }
}
