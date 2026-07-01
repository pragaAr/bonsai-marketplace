@if ($paginator->hasPages())
  <nav role="navigation" aria-label="Pagination Navigation"
    class="flex items-center justify-between flex-wrap gap-4">

    <div class="hidden sm:block">
      <p class="text-xs text-[#2d3e2fec] leading-5">
        Showing
        @if ($paginator->firstItem())
          <span
            class="font-semibold">{{ $paginator->firstItem() }}</span>
          to
          <span
            class="font-semibold">{{ $paginator->lastItem() }}</span>
        @else
          {{ $paginator->count() }}
        @endif
        of
        <span
          class="font-semibold">{{ $paginator->total() }}</span>
        results
      </p>
    </div>

    <div
      class="flex justify-between w-full sm:w-auto items-center">
      <span
        class="relative z-0 inline-flex rounded-md w-full sm:w-auto justify-center">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
          <span aria-disabled="true" aria-label="Previous">
            <span
              class="relative inline-flex items-center px-2 py-1.5 text-xs font-medium text-gray-400 bg-white border border-gray-300 rounded-l-md cursor-default select-none"
              aria-hidden="true">
              <svg class="w-4 h-4" fill="currentColor"
                viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                  clip-rule="evenodd" />
              </svg>
            </span>
          </span>
        @else
          <button type="button" wire:click="previousPage"
            wire:loading.attr="disabled" rel="prev"
            class="relative inline-flex items-center px-2 py-1.5 text-xs font-medium text-[#2d3e2fec] bg-white border border-gray-300 rounded-l-md cursor-pointer hover:bg-gray-50 focus:z-10 focus:outline-none"
            aria-label="Previous">
            <svg class="w-4 h-4" fill="currentColor"
              viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                clip-rule="evenodd" />
            </svg>
          </button>
        @endif

        {{-- Pagination Elements (Nomor Halaman) --}}
        @foreach ($elements as $element)
          {{-- "Three Dots" Separator --}}
          @if (is_string($element))
            <span aria-disabled="true"
              class="relative inline-flex items-center px-2.5 py-1.5 -ml-px text-xs font-medium text-gray-400 bg-white border border-gray-300 cursor-default select-none">{{ $element }}</span>
          @endif

          {{-- Array Of Links --}}
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <span aria-current="page"
                  style="background-color: #2d3e2fec;"
                  class="relative inline-flex items-center px-3 py-1.5 -ml-px text-xs font-semibold text-white border border-[#2d3e2fec] cursor-default select-none">{{ $page }}</span>
              @else
                <button type="button"
                  wire:click="gotoPage({{ $page }})"
                  wire:loading.attr="disabled"
                  class="relative inline-flex items-center px-3 py-1.5 -ml-px text-xs font-medium text-[#2d3e2fec] bg-white border border-gray-300 cursor-pointer hover:bg-gray-50 focus:z-10 focus:outline-none"
                  aria-label="Go to page {{ $page }}">
                  {{ $page }}
                </button>
              @endif
            @endforeach
          @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
          <button type="button" wire:click="nextPage"
            wire:loading.attr="disabled" rel="next"
            class="relative inline-flex items-center px-2 py-1.5 -ml-px text-xs font-medium text-[#2d3e2fec] bg-white border border-gray-300 rounded-r-md cursor-pointer hover:bg-gray-50 focus:z-10 focus:outline-none"
            aria-label="Next">
            <svg class="w-4 h-4" fill="currentColor"
              viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd" />
            </svg>
          </button>
        @else
          <span aria-disabled="true" aria-label="Next">
            <span
              class="relative inline-flex items-center px-2 py-1.5 -ml-px text-xs font-medium text-gray-400 bg-white border border-gray-300 rounded-r-md cursor-default select-none"
              aria-hidden="true">
              <svg class="w-4 h-4" fill="currentColor"
                viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd" />
              </svg>
            </span>
          </span>
        @endif
      </span>
    </div>
  </nav>
@endif
