<?php

namespace App\Livewire;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public $isOpen = false;

    public array $selectedItemIds = [];

    public bool $selectionInitialized = true;

    #[On('open-cart')]
    public function openDrawer()
    {
        $this->isOpen = true;
    }

    #[On('close-cart')]
    public function closeDrawer()
    {
        $this->isOpen = false;
    }

    #[On('add-to-cart')]
    public function addToCart($productId)
    {
        if (! Auth::check()) {
            $this->dispatch('toast',
                message: 'Silahkan login dahulu untuk menambahkan ke keranjang.',
                actionText: 'Login',
                actionUrl: route('login'),
                duration: 4000
            );

            return;
        }

        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        $cart = Auth::user()->cart()->firstOrCreate();
        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            if ($product->status !== 'approved' || $cartItem->qty >= $product->stock) {
                $message = $product->status !== 'approved'
                    ? "'{$product->name}' sedang tidak tersedia."
                    : "Stok '{$product->name}' hanya tersedia {$product->stock} item.";
                $this->dispatch('toast', message: $message, duration: 3000);

                return;
            }

            $cartItem->increment('qty');
        } else {
            if ($product->status !== 'approved' || $product->stock < 1) {
                $this->dispatch('toast', message: "'{$product->name}' sedang habis.", duration: 3000);

                return;
            }

            $cart->items()->create(['product_id' => $productId, 'qty' => 1]);
        }

        $this->dispatch('cart-updated');

        // Log Activity using Spatie Activitylog
        activity()
            ->performedOn($product)
            ->event('cart_added')
            ->log("Added '{$product->name}' to shopping cart");

        // Dispatch browser toast notification
        $this->dispatch('toast', message: "'{$product->name}' dimasukkan ke keranjang!", duration: 3000);

    }

    public function updateQuantity($productId, $qty)
    {
        $cart = Auth::user()->cart;
        $cartItem = $cart?->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            if ($qty <= 0) {
                $cartItem->delete();
            } else {
                $product = Product::find($productId);
                if (! $product || $product->status !== 'approved' || $product->stock < 1) {
                    $cartItem->delete();
                } else {
                    $cartItem->update(['qty' => min((int) $qty, $product->stock)]);
                }
            }
            $this->dispatch('cart-updated');
        }
    }

    public function removeFromCart($productId)
    {
        $cart = Auth::user()->cart;
        $cartItem = $cart?->items()->with('product')->where('product_id', $productId)->first();

        if ($cartItem) {
            $this->selectedItemIds = array_values(array_diff($this->selectedItemIds, [(string) $productId, (int) $productId]));
            $name = $cartItem->product?->name ?? 'Item';
            $cartItem->delete();
            $this->dispatch('cart-updated');

            $this->dispatch('toast', message: "'{$name}' dihapus dari keranjang", duration: 3000);
        }
    }

    public function clearCart()
    {
        Auth::user()->cart?->items()->delete();
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Keranjang berhasil dibersihkan', duration: 3000);
    }

    public function checkout()
    {
        $cart = Auth::user()->cart;
        if (! $cart || ! $cart->items()->exists()) {
            return;
        }

        $selectedIds = collect($this->selectedItemIds)->map(fn ($id) => (int) $id)->filter()->unique();
        if ($selectedIds->isEmpty()) {
            $this->dispatch('toast', message: 'Pilih minimal satu produk untuk checkout.', duration: 3000);

            return;
        }

        $hasUnavailableItem = $cart->items()
            ->whereIn('product_id', $selectedIds)
            ->where(function ($query) {
                $query->whereHas('product', fn ($productQuery) => $productQuery
                    ->where('status', '!=', 'approved')
                    ->orWhere('stock', '<=', 0))
                    ->orWhereDoesntHave('product');
            })
            ->exists();

        if ($hasUnavailableItem) {
            $this->dispatch('toast', message: 'Hapus produk yang tidak tersedia sebelum checkout.', duration: 4000);

            return;
        }

        $this->isOpen = false;

        return $this->redirect(route('checkout', ['items' => $selectedIds->implode(',')]), navigate: true);
    }

    public function downloadInvoice()
    {
        $cart = Auth::user()->cart;
        $cartItems = $cart?->items()->with('product')->get() ?? collect();
        if ($cartItems->isEmpty()) {
            return;
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->qty);

        // Log PDF generation in Activitylog
        activity()
            ->event('invoice_downloaded')
            ->log('Downloaded PDF Invoice for a total of Rp '.number_format($subtotal, 0, ',', '.'));

        $pdf = Pdf::loadView('pdf.invoice', [
            'cart' => $cartItems->map(fn ($item) => [
                'name' => $item->product->name,
                'price' => $item->product->price,
                'qty' => $item->qty,
            ])->all(),
            'subtotal' => $subtotal,
            'invoiceNumber' => 'INV-'.date('Ymd').'-'.strtoupper(Str::random(4)),
            'date' => now()->format('d M Y'),
        ]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'invoice.pdf'
        );
    }

    public function render()
    {
        $user = Auth::user();
        if (! $user) {
            return view('livewire.shop.cart-drawer', [
                'cartItems' => [],
                'subtotal' => 0,
            ]);
        }

        $cart = $user->cart;
        $items = $cart?->items()->with('product')->get() ?? collect();
        $this->selectedItemIds = array_values(array_intersect(
            array_map('strval', $this->selectedItemIds),
            $items->pluck('product_id')->map(fn ($id) => (string) $id)->all()
        ));

        $cartItems = $items->mapWithKeys(function ($item) {
            $product = $item->product;
            $isAvailable = $product
                && $product->status === 'approved'
                && $product->stock > 0;

            return [$item->product_id => [
                'id' => $item->product_id,
                'name' => $product?->name ?? 'Produk tidak tersedia',
                'price' => $product?->price ?? 0,
                'image' => $product?->image_url ?? asset('images/bonsai-1.png'),
                'qty' => $item->qty,
                'stock' => $product?->stock ?? 0,
                'isAvailable' => $isAvailable,
                'isSelected' => in_array((string) $item->product_id, array_map('strval', $this->selectedItemIds), true),
            ]];
        })->all();

        $subtotal = collect($cartItems)
            ->filter(fn ($item) => $item['isAvailable'] && $item['isSelected'])
            ->sum(fn ($item) => $item['price'] * $item['qty']);

        return view('livewire.shop.cart-drawer', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
        ]);
    }
}
