@props(['target'])

<button type="submit" wire:loading.attr="disabled"
  wire:target="{{ $target }}"
  {{ $attributes->merge([
      'class' =>
          'flex-1 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 disabled:opacity-50 inline-flex items-center justify-center gap-2 cursor-pointer',
  ]) }}>

  <x-icons.spinner wire:loading :wire:target="$target"
    class="h-3.5 w-3.5 text-current" />

  <span>{{ $slot }}</span>

</button>
