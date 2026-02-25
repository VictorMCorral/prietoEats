<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;

class AdminController extends Controller
{
    public function ordersIndex()
    {
        $offers = Offer::with(['productOrders.product', 'productOrders.order.user', 'productsOffer.product'])
                       ->whereDate('date_delivery', '>=', today())
                       ->latest('date_delivery')
                       ->get();

        $dataByOffer = [];

        foreach ($offers as $offer) {
            $users = [];

            foreach ($offer->productOrders as $po) {
                $uId = $po->order->user->id;
                $pId = $po->product->id;

                $users[$uId]['name'] = $po->order->user->name;
                $users[$uId]['products'][$pId] = [
                    'name'     => $po->product->name,
                    'price'    => $po->product->price,
                    'quantity' => ($users[$uId]['products'][$pId]['quantity'] ?? 0) + $po->quantity,
                ];

                $users[$uId]['total'] = ($users[$uId]['total'] ?? 0) + ($po->quantity * $po->product->price);
            }

            $dataByOffer[] = [
                'offer'    => $offer,
                'products' => $offer->productsOffer,
                'users'    => $users
            ];
        }

        return view("admin.orders.index", compact('dataByOffer'));
    }
}
