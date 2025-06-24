<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Resources\ProductoResource;

class ProductoController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            return response()->json(Producto::with(['categoria', 'proveedor','embalaje'])->where('active', 1))->get();
        } else {
            $query = Producto::with(['categoria', 'proveedor','embalaje'])
                    ->where('active', 1);
            if ($request->filled('categoria_id')) {
                $query->where('categoria_id', $request->categoria_id);
            }
            if ($request->filled('proveedor_id')) {
                $query->where('proveedor_id', $request->proveedor_id);
            }
            if ($request->filled('embalaje_id')) {
                $query->where('embalaje_id', $request->embalaje_id);
            }
            return ProductoResource::collection($query->paginate(2));
        }
    }

    public function show($id){
        $producto = Producto::with(['categoria', 'proveedor'])->findOrFail($id);
        return new ProductoResource($producto);
    }

}
