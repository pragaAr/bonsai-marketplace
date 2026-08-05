@props(['paginator'])

@if ($paginator->hasPages())
  <div class="mt-12 flex justify-center gap-2">
    <button type="button"
      wire:click="previousPage('{{ $paginator->getPageName() }}')"
      wire:loading.attr="disabled"
      wire:target="previousPage, nextPage, gotoPage"
      @if ($paginator->onFirstPage()) disabled @endif
      aria-label="Previous page"
      class="min-w-[44px] min-h-[44px] rounded-lg border border-primary/15 p-2.5 flex items-center justify-center hover:bg-primary/5 transition-colors disabled:opacity-30 disabled:pointer-events-none cursor-pointer">
      <x-icons.arrow-left class="w-4 h-4" />
    </button>

    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
      <button type="button"
        wire:click="gotoPage({{ $i }}, '{{ $paginator->getPageName() }}')"
        wire:loading.attr="disabled"
        wire:target="previousPage, nextPage, gotoPage"
        aria-label="Page {{ $i }}"
        class="min-w-[44px] min-h-[44px] rounded-lg border border-primary/15 text-sm font-medium flex items-center justify-center hover:bg-primary/5 transition-colors cursor-pointer {{ $i === $paginator->currentPage() ? 'bg-primary text-cream border-primary' : '' }}">
        {{ $i }}
      </button>
    @endfor

    <button type="button"
      wire:click="nextPage('{{ $paginator->getPageName() }}')"
      wire:loading.attr="disabled"
      wire:target="previousPage, nextPage, gotoPage"
      @if (!$paginator->hasMorePages()) disabled @endif
      aria-label="Next page"
      class="min-w-[44px] min-h-[44px] rounded-lg border border-primary/15 p-2.5 flex items-center justify-center hover:bg-primary/5 transition-colors disabled:opacity-30 disabled:pointer-events-none cursor-pointer">
      <x-icons.arrow-right class="w-4 h-4" />
    </button>
  </div>
@endif
