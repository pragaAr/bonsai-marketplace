@props([])

<div class="flex items-center justify-between mb-4">
  <h3
    class="font-heading font-semibold text-lg text-primary">
    {{ $slot }}
  </h3>

  <button type="button"
    {{ $attributes->merge([
        'class' =>
            'text-primary/60 hover:text-primary/80 rounded-lg p-1 text-xl transition-colors cursor-pointer',
    ]) }}>
    &times;
  </button>
</div>
