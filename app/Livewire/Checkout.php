<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Checkout extends Component
{
    public ?Product $product = null;

    public int $quantity = 1;

    public string $paymentMethod = 'rekening_bersama';

    public string $buyerName = '';

    public string $whatsapp = '';

    public string $address = '';

    public string $notes = '';

    public bool $orderSubmitted = false;

    public string $orderNumber = '';

    public array $cartItems = [];

    public function mount(?string $slug = null): void
    {
        $user = Auth::user();

        $this->buyerName = $user?->name ?? '';
        $this->whatsapp = $user?->whatsapp ?? '';
        $this->address = $user?->address ?? '';

        if ($slug) {
            $this->product = Product::where('slug', $slug)->firstOrFail();
        } else {
            $cartItems = $user?->cart?->items()->with('product')->get() ?? collect();
            $selectedIds = collect(explode(',', (string) request()->query('items')))
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->all();

            if ($selectedIds) {
                $cartItems = $cartItems->whereIn('product_id', $selectedIds)->values();
            }

            $this->cartItems = $cartItems->mapWithKeys(function ($item) {
                $product = $item->product;

                return [$item->product_id => [
                    'id' => $item->product_id,
                    'name' => $product?->name ?? 'Produk tidak tersedia',
                    'price' => $product?->price ?? 0,
                    'image' => $product?->image_url ?? asset('images/bonsai-1.png'),
                    'qty' => $item->qty,
                    'isAvailable' => $product
                        && $product->status === 'approved'
                        && $product->stock > 0,
                ]];
            })->all();

            if (empty($this->cartItems)) {
                $this->redirect(route('shop'), navigate: true);
            }
        }
    }

    public function updatedQuantity($value): void
    {
        $this->quantity = max(1, (int) $value);
    }

    public function submitOrder(): void
    {
        $rules = [
            'paymentMethod' => ['required', 'in:rekening_bersama,qris,cod'],
            'buyerName' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];

        if ($this->product) {
            $rules['quantity'] = ['required', 'integer', 'min:1', 'max:99'];
        }

        $validated = $this->validate($rules, [
            'buyerName.required' => 'Nama penerima wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'address.required' => 'Alamat pengiriman wajib diisi.',
            'paymentMethod.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        if (! $this->product && collect($this->cartItems)->contains(fn ($item) => ! $item['isAvailable'])) {
            $this->dispatch('toast', message: 'Ada produk yang tidak tersedia di keranjang.', duration: 4000);

            return;
        }

        if ($this->product) {
            $this->quantity = (int) $validated['quantity'];
        }
        $this->orderNumber = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        $this->orderSubmitted = true;

        if ($this->product) {
            activity()
                ->performedOn($this->product)
                ->event('mock_order_created')
                ->log("Created mock order {$this->orderNumber} for '{$this->product->name}'");
        } else {
            activity()
                ->event('mock_order_created')
                ->log("Created mock order {$this->orderNumber} for cart checkout");

            Auth::user()?->cart?->items()->delete();
            $this->dispatch('cart-updated');
        }

        $this->dispatch('toast', message: 'Pesanan mock berhasil dibuat.', duration: 3000);
    }

    public function getSubtotalProperty(): int
    {
        if ($this->product) {
            return $this->product->price * $this->quantity;
        }

        return collect($this->cartItems)->sum(fn ($item) => $item['price'] * $item['qty']);
    }

    public function getServiceFeeProperty(): int
    {
        return $this->paymentMethod === 'rekening_bersama' ? 10000 : 0;
    }

    public function getTotalProperty(): int
    {
        return $this->subtotal + $this->serviceFee;
    }

    #[Layout('layouts.app')]
    #[Title('Checkout')]
    public function render()
    {
        return view('livewire.shop.checkout');
    }
}
