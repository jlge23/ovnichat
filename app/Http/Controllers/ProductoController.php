<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource;
use Illuminate\Http\Request;
use App\Models\Producto;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with(['categoria', 'proveedor', 'embalaje'])
            ->orderBy('nombre', 'asc')
            ->limit(15)
            ->get();

        return Inertia::render("Products/Products", [
            "productos" => $productos,
        ]);
    }

    public function show($id)
    {
        $producto = Producto::with(['categoria', 'proveedor'])->findOrFail($id);
        return new ProductoResource($producto);
    }
}
