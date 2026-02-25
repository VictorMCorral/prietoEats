<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOffer;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class offerController extends Controller
{

    public function index()
    {
        $offers = Offer::with("productsOffer.product")->get();
        //dd($productsOffers);
        return view("admin.offers.index", ["offers" => $offers]);;
    }

    public function create()
    {
        $productos = Product::All();
        //dd($products);
        return view("admin.offers.create", ["productos" => $productos]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "date_delivery" => "required|date_format:Y-m-d|after:today",
            "time_delivery" => "required|string|max:255",
            "dish_selected" => "required|array|min:1",
            "dish_selected.*" => "integer|distinct|exists:products,id",
        ]);

        DB::transaction(function () use ($validated) {
            $oferta = Offer::create([
                "time_delivery" => $validated["time_delivery"],
                "date_delivery" => $validated["date_delivery"],
            ]);

            $productsIds = $validated["dish_selected"];

            $rows = collect($productsIds)->map(fn($id) => [
                "product_id" => $id
            ])->values()->all();

            $oferta->productsOffer()->createMany($rows);
        });
        return redirect()->route('admin.offers.index');
    }

    public function show(string $id){}

    public function edit(string $id){}

    public function update(Request $request, string $id){}

    public function destroy(string $id)
    { {
            try {
                $offer = Offer::findOrFail($id);
                $offer->delete();
            } catch (\Throwable $th) {
                return back()->withErrors(['error' => 'No se pudo elimnar la oferta.']);
            }

            return redirect()->route('admin.offers.index');
        }
    }
}
