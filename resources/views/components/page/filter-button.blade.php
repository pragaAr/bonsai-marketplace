@props([
    'target' => 'openFilter',
])

<button wire:click="{{ $target }}"
  wire:loading.attr="disabled"
  wire:target="{{ $target }}"
  {{ $attributes->merge(['class' => 'w-full sm:w-auto px-3 py-2 bg-black/50 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition-smooth cursor-pointer inline-flex items-center justify-center gap-1 disabled:opacity-50 self-start sm:self-center']) }}>

  <x-icons.arrow-up-down wire:loading.remove
    wire:target="{{ $target }}"
    class="h-3.5 w-3.5 text-current" />

  <x-icons.spinner wire:loading
    wire:target="{{ $target }}"
    class="h-3.5 w-3.5 text-current" />

  <span>{{ $slot->isEmpty() ? 'Filter' : $slot }}</span>
</button>
