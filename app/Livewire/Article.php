<?php

namespace App\Livewire;

use App\Models\JournalEntry;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Article extends Component
{
  #[Layout('layouts.app')]
  #[Title('Artikel')]
  public function render()
  {
    $journals = JournalEntry::orderBy('published_date', 'desc')->get();

    return view('livewire.landing-page.article', compact('journals'));
  }
}
