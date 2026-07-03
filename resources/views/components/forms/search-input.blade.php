@props([
    'model' => 'search',
    'placeholder' => 'Cari...',
])

<input type="search"
  wire:model.live.debounce.300ms="{{ $model }}"
  placeholder="{{ $placeholder }}"
  {{ $attributes->merge([
      'class' =>
          'px-4 py-2 rounded-xl bg-white/50 border border-primary/20 text-sm focus:border-primary/40 outline-none w-full',
  ]) }}>
