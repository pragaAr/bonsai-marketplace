<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class CareGuide extends Component
{
    #[Layout('layouts.app')]
    #[Title('Panduan Perawatan')]
    public function render()
    {
        return view('livewire.landing-page.care-guide');
    }
}
