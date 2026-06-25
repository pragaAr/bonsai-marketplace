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

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['qty']++;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->getFirstMediaUrl('images') ?: asset('images/bonsai-1.png'),
                'qty' => 1,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');

        // Log Activity using Spatie Activitylog
        activity()
            ->performedOn($product)
            ->event('cart_added')
            ->log("Added '{$product->name}' to shopping cart");

        // Dispatch browser toast notification
        $this->dispatch('toast', message: "'{$product->name}' dimasukkan ke keranjang!", duration: 3000);

        // Auto open drawer to delight user (Disabled as per user request)
        // $this->isOpen = true;
    }

    public function updateQuantity($productId, $qty)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($qty <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['qty'] = $qty;
            }
            session()->put('cart', $cart);
            $this->dispatch('cart-updated');
        }
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $name = $cart[$productId]['name'];
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->dispatch('cart-updated');

            $this->dispatch('toast', message: "'{$name}' dihapus dari keranjang", duration: 3000);
        }
    }

    public function clearCart()
    {
        session()->forget('cart');
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Keranjang berhasil dibersihkan', duration: 3000);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return;
        }

        $this->isOpen = false;
        return $this->redirect(route('checkout'), navigate: true);
    }

    public function downloadInvoice()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return;
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);

        // Log PDF generation in Activitylog
        activity()
            ->event('invoice_downloaded')
            ->log('Downloaded PDF Invoice for a total of Rp '.number_format($subtotal, 0, ',', '.'));

        $pdf = Pdf::loadView('pdf.invoice', [
            'cart' => $cart,
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
        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);

        return view('livewire.cart-drawer', [
            'cartItems' => $cart,
            'subtotal' => $subtotal,
        ]);
    }
}
