<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;


class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = $this->productos();
        // dd($productos);
        return view("admin.products.mostrar", ["productos" => $productos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //NO ES NECESARIO
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('img', 'public');
            }

            Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'image' => $imagePath ?? '',
            ]);
        } catch (\Throwable $th) {
            error_log("algo ha pasado: " . $th->getMessage());
            return back()->withErrors(['msg' => 'Ocurrió un error al guardar el producto']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //NO ES NECESARIO
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $producto = Product::findOrFail($id);
        error_log("Has mostrado el FORMULARIO del producto: " . $id);
        return view("admin.products.edit", ["producto" => $producto]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $producto = Product::findOrFail($id);
        $imagePath = $producto->image;
        //dd($imagePath);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('img', 'public');
        }

        $producto->name = $request->name;
        $producto->price = $request->price;
        $producto->description = $request->description;
        $producto->image = $imagePath;


        //dd($producto);
        $producto->update();

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $producto = Product::findOrFail($id);
            $producto->delete();
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'No se pudo elimnar el producto.']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente');
    }

    public function productos()
    {
        return Product::all();
    }
}
