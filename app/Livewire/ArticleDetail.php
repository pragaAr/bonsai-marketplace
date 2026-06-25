<?php

namespace App\Livewire;

use App\Models\JournalEntry;
use Livewire\Attributes\Title;
use Livewire\Component;

class ArticleDetail extends Component
{
    public $article;

    public function mount($slug)
    {
        // Load article with the author relationship preloaded
        $this->article = JournalEntry::with('author')->where('slug', $slug)->firstOrFail();

        // Log article view activity if activity log is active
        if (function_exists('activity')) {
            activity()
                ->performedOn($this->article)
                ->event('article_viewed')
                ->log("Viewed article '{$this->article->title}'");
        }
    }

    #[Title('Detail Artikel')]
    public function render()
    {
        // Fetch related articles from the same category
        $relatedArticles = JournalEntry::where('category', $this->article->category)
            ->where('id', '!=', $this->article->id)
            ->take(3)
            ->get();

        // If we have fewer than 3, backfill with other articles
        if ($relatedArticles->count() < 3) {
            $excludeIds = $relatedArticles->pluck('id')->push($this->article->id)->toArray();
            $backfill = JournalEntry::whereNotIn('id', $excludeIds)
                ->take(3 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->concat($backfill);
        }

        return view('livewire.article-detail', [
            'relatedArticles' => $relatedArticles,
        ])->layout('layouts.app');
    }
}
