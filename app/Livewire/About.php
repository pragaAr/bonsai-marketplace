<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class About extends Component
{
  #[Layout('layouts.app')]
  #[Title('Tentang Kami')]
  public function render()
  {
    return view('livewire.landing-page.about');
  }
}
