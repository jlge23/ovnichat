<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            return response()->json(Producto::with(['categoria', 'proveedor','embalaje'])->where('active', 1))->get();
        } else {
            $productos = Producto::with(['categoria', 'proveedor','embalaje'])->get();
            return view('productos.index', compact('productos'));
        }
    }

    public function show($id){
        $producto = Producto::with(['categoria', 'proveedor'])->findOrFail($id);
        return new ProductoResource($producto);
    }

}
