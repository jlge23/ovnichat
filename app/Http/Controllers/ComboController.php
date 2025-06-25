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
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        $combo = Combo::create($validated);

        foreach ($validated['productos'] as $prod) {
            $combo->productos()->attach($prod['id'], ['cantidad' => $prod['cantidad']]);
        }

        return response()->json($combo->load('productos'), 201);
    }

    public function edit($id){
        $productos = Producto::where('stock_actual', '>', 0)->get(); // tus 37 productos
        $combo = Combo::with('productos')->find($id);
        $productosDelCombo = $combo->productos; // los asignados
        $productosAsignados = $productosDelCombo->pluck('id')->toArray();
        $productosMarcados = $productos->map(function ($producto) use ($productosAsignados) {
            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'stock_actual' => $producto->stock_actual,
                'asignado' => in_array($producto->id, $productosAsignados),
            ];
        });
        return view('combos.edit' ,compact('productosMarcados','combo'));
    }

    public function show(Combo $combo)
    {
        return response()->json($combo->load('productos'));
    }

    public function update(Request $request, Combo $combo)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'status' => 'in:activo,inactivo,agotado',
            'productos' => 'nullable|array',
            'productos.*.id' => 'required_with:productos|exists:productos,id',
            'productos.*.cantidad' => 'required_with:productos|integer|min:1',
        ]);

        $combo->update($validated);

        if (isset($validated['productos'])) {
            $combo->productos()->sync(
                collect($validated['productos'])->mapWithKeys(fn($p) => [$p['id'] => ['cantidad' => $p['cantidad']]])
            );
        }

        return response()->json($combo->load('productos'));
    }

    public function destroy(Combo $combo)
    {
        $combo->delete();
        return response()->json(['mensaje' => 'Combo eliminado'], 200);
    }

    public function run()
    {
        $combo = Combo::create([
            'nombre' => 'Día del Padre',
            'descripcion' => 'Combo especial para papá: desayuno con avena, café gourmet y papas sabor jamón.',
            'precio' => 9.99,
            'status' => 'activo',
        ]);

        $combo->productos()->attach([
            1 => ['cantidad' => 1], // AVENA EN HOJUELAS
            2 => ['cantidad' => 1], // CAFÉ TOSTADO Y MOLIDO GOURMET
            10 => ['cantidad' => 2], // HOJUELAS DE PAPAS
        ]);
    }

}
