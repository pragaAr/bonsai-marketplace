@props([
    'type' => 'button',
])

<button type="{{ $type }}"
  {{ $attributes->merge([
      'class' =>
          'flex-1 px-4 py-2.5 border border-primary/10 bg-primary/10 rounded-xl text-sm font-medium text-primary hover:bg-primary/5 cursor-pointer',
  ]) }}>
  {{ $slot }}
</button>
