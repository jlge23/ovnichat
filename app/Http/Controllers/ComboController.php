<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use App\Models\Producto;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    public function index(){
        $combos = Combo::with('productos')->withCount('productos')->get();
        return view('combos.index',compact('combos'));
    }

    /* public function json(){
        $combos = Combo::with('productos')->withCount('productos')->get();
        foreach($combos as $combo){
            $data['data'][] = array(
                'id' => $combo->id,
                'nonbre' => $combo->nombre,
                'descripcion' => $combo->descripcion,
                'precio' => $combo->precio,
                'productos' => $combo->productos,
                'status' => $combo->status
            );
        }
        if(empty($data)){
            $data['data'] = [];
            return response()->json($data);
        }else{
            return response()->json($data);
        }
    } */

    public function create(){
        $productos = Producto::where("stock_actual",">",0)->with('categoria')->get();
        return view('combos.create',compact('productos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'status' => 'required|in:activo,inactivo,agotado',
            /* 'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1', */
        ]);

        $combo = Combo::create($validated);
        /*
        foreach ($validated['productos'] as $prod) {
            $combo->productos()->attach($prod['id'], ['cantidad' => $prod['cantidad']]);
        } */
        return redirect()->route('combos.index');
        //return response()->json($combo->load('productos'), 201);
    }

    public function edit($id){
        $productos = Producto::where('stock_actual', '>', 0)->get();
        $combo = Combo::with('productos')->find($id);
        $productosDelCombo = $combo->productos; // productos asignados
        $productosAsignados = $productosDelCombo->pluck('id')->toArray();
        $productosMarcados = $productos->map(function ($producto) use ($productosDelCombo, $productosAsignados) {
            $asignado = in_array($producto->id, $productosAsignados);
            $cantidad = 0;
            if ($asignado) {
                // Busca el producto asignado en la colección y accede a la cantidad desde la tabla pivote
                $cantidad = optional($productosDelCombo->firstWhere('id', $producto->id)->pivot)->cantidad ?? 0;
            }
            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'stock_actual' => $producto->stock_actual,
                'asignado' => $asignado,
                'cantidad' => $cantidad
            ];
        });
        $data = [];
        foreach ($productosMarcados as $producto) {
            $data['data'][] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'descripcion' => $producto['descripcion'],
                'stock_actual' => $producto['stock_actual'],
                'asignado' => $producto['asignado'],
                'cantidad' => $producto['cantidad']
            ];
        }
        if(empty($data)){
            $data['data'] = [];
            return response()->json($data);
        }else{
            return response()->json($data);
        }

        //return view('combos.edit' ,compact('productosMarcados','combo'));
    }

    public function show(Combo $combo)
    {
        return response()->json($combo->load('productos'));
    }

    public function update(Request $request, Combo $combo)
    {
        $validated = $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);
        // Prepara los datos para sync()
        $productosSincronizados = [];

        foreach ($validated['productos'] as $id => $producto) {
            $productosSincronizados[$id] = [
                'cantidad' => $producto['cantidad']
            ];
        }
        // Asocia los productos con cantidades a través de la tabla pivote
        $combo->productos()->sync($productosSincronizados);
        return redirect()->route('combos.index')->with('success', 'Combo actualizado correctamente.');
    }

    public function destroy(Combo $combo)
    {
        $combo->delete();
        return redirect()->route('combos.index');
    }
}
