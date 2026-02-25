<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Models\ProductOrder;
use Illuminate\Support\Facades\Auth;


class cartController extends Controller
{
    public function cartShow()
    {
        $carrito = session("carrito", []);

        $offerIds = array_keys($carrito);


        $productOfferId = [];
        foreach ($carrito as $offerId => $items) {
            $productOfferId = array_merge($productOfferId, array_keys($items));
        }

        $productOfferId = array_unique($productOfferId);
        // dd($offerIds);
        $offersById = Offer::whereIn("id", $offerIds)
            ->get()
            ->keyBy("id");

        $productsOffersById = ProductOffer::with("product")
            ->whereIn("id", $productOfferId)
            ->get()
            ->keyBy("id");

        return view("carrito", compact("carrito", "offersById", "productsOffersById"));
    }

    public function cartAdd($productOfferId)
    {
        $carrito = session()->get("carrito", []);

        // 1. Obtenemos la relación producto-oferta
        $productOffer = ProductOffer::with('product')->findOrFail($productOfferId);
        $offerId = $productOffer->offer_id;
        $productName = $productOffer->product->name;

        // 2. Aseguramos que el índice de la oferta exista en el carrito
        if (!isset($carrito[$offerId])) {
            $carrito[$offerId] = [];
        }

        // 3. Añadimos o incrementamos el producto específico de esa oferta
        if (isset($carrito[$offerId][$productOfferId])) {
            $carrito[$offerId][$productOfferId]++;
        } else {
            $carrito[$offerId][$productOfferId] = 1;
        }

        // 4. Guardamos y enviamos feedback
        session()->put("carrito", $carrito);
        session()->flash('success', "✓ {$productName} añadido correctamente");

        return redirect()->back(); // Mejor volver atrás que a la home siempre
    }

    public function cartRemove($productOfferId)
    {
        $carrito = session()->get("carrito", []);
        $productOffer = ProductOffer::findOrFail($productOfferId);
        $offerId = $productOffer->offer_id;

        if (isset($carrito[$offerId])) {
            if (isset($carrito[$offerId][$productOfferId])) {
                unset($carrito[$offerId][$productOfferId]);
            }

            if (count($carrito[$offerId]) === 0) {
                unset($carrito[$offerId]);
            }
        }

        session()->put("carrito", $carrito);

        return redirect()->route("cartShow");
    }

    public function cartClear()
    {
        session()->forget("carrito");

        return redirect()->route("cartShow");
    }

    public function cartAddOne($productOfferId)
    {
        $carrito = session()->get("carrito", []);
        $productOffer = ProductOffer::findOrFail($productOfferId);
        $offerId = $productOffer->offer_id;

        if (isset($carrito[$offerId])) {
            if (isset($carrito[$offerId][$productOfferId])) {
                $carrito[$offerId][$productOfferId]++;
            }
        }

        session()->put("carrito", $carrito);

        return redirect()->route("cartShow");
    }


    public function cartRemoveOne($productOfferId)
    {
        $carrito = session()->get("carrito", []);
        $productOffer = ProductOffer::findOrFail($productOfferId);
        $offerId = $productOffer->offer_id;

        if (isset($carrito[$offerId])) {
            if (isset($carrito[$offerId][$productOfferId])) {
                $carrito[$offerId][$productOfferId]--;
            }
        }

        session()->put("carrito", $carrito);

        return redirect()->route("cartShow");
    }

    public function cartOrder()
    {
        $carrito = session('carrito', []);
        $total = 0;
        $order = Order::create([
            "user_id" => Auth::id(),
            "total" => $total
        ]);

        foreach ($carrito as $offerId => $productos) {
            foreach ($productos as $productOfferId => $cantidad) {
                $productOffer = ProductOffer::findOrFail($productOfferId);
                $producto = $productOffer->product;
                $total += $producto->price * $cantidad;

                // Convertir offerId a entero para asegurar que se guarda correctamente
                $offerId = (int) $offerId;

                ProductOrder::create([
                    "order_id" => $order->id,
                    "product_id" => $producto->id,
                    "offer_id" => $offerId,
                    "quantity" => $cantidad
                ]);
            }
        }

        $order->total = $total;
        $order->save();

        $this->cartClear();

        return redirect()->intended('/');
    }
}
