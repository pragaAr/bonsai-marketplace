@props([
  'product',
  'label' => null,
  'iconClass' => 'w-4 h-4',
])

@php
  $whatsappUrl = 'https://wa.me/6281234567890?text=' . urlencode("Hi, saya tertarik dengan {$product->name} (Rp " . number_format($product->price, 0, ',', '.') . ')');
@endphp

<a
  @auth
  href="{{ $whatsappUrl }}"
  target="_blank"
  rel="noopener noreferrer"
  @else
  href="{{ route('login') }}"
  @click.prevent="$dispatch('toast', { message: @js('Silakan login terlebih dahulu untuk chat via WhatsApp.'), duration: 3000, actionText: 'Login', actionUrl: @js(route('login')) })"
  @endauth
  aria-label="Chat WhatsApp about {{ $product->name }}"
  {{ $attributes }}
>
  <svg 
    class="{{ $iconClass }}" 
    viewBox="0 0 24 24" 
    fill="currentColor"
  >
    <path 
      d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"
    />
    <path 
      d="M12 0C5.373 0 0 5.373 0 12c0 2.12.553 4.11 1.519 5.838L0 24l6.335-1.652A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.82a9.78 9.78 0 01-5.283-1.545l-.379-.226-3.93 1.025 1.05-3.83-.248-.394A9.78 9.78 0 012.18 12c0-5.422 4.398-9.82 9.82-9.82s9.82 4.398 9.82 9.82-4.398 9.82-9.82 9.82z"
    />
  </svg>

  @if($label)
    <span>{{ $label }}</span>
  @endif
</a>
