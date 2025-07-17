<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource;
use App\Models\Categoria;
use App\Models\Embalaje;
use App\Models\Marca;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\UnidadMedida;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            //return response()->json(Producto::with(['categoria', 'proveedor','embalaje'])->where('active', 1))->get();
            return response()->json(Producto::with(['categoria', 'proveedor','embalaje'])->where('active', true))->get();
        } else {
            $productos = Producto::get();
            return view('productos.index', compact('productos'));
        }
    }

    public function create(){
        $unidadesMedidas = UnidadMedida::all();
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        $embalajes = Embalaje::all();
        return view("productos.create", compact('unidadesMedidas','marcas','categorias','proveedores','embalajes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gtin' => ['nullable','digits_between:8,14','regex:/^\d+$/'],
            'producto' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'precio_detal' => 'required|numeric|min:0|max:999999.99',
            'precio_embalaje' => 'nullable|numeric|min:0|max:999999.99',
            'costo_detal' => 'nullable|numeric|min:0|max:999999.99',
            'stock_actual' => 'required|integer|min:0',
            'marca_id' => 'nullable|exists:marcas,id',
            'unidades_por_embalaje' => 'nullable|integer|min:1',
            'categoria_id' => 'nullable|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'embalaje_id' => 'nullable|exists:embalajes,id',
            'unidad_medida_id' => 'nullable|exists:unidad_medidas,id',
            'image'=>'image|mimes:png,gif,jpeg,jpg|max:3072',
            'active' => 'required|boolean',
        ]);
        if ($request->hasFile('image') && $request->image) {
            $file = $request->file('image');
            $imagen = $file->getClientOriginalName();
            //$type = $file->getClientMimeType(); // Tipo MIME del archivo
            $extension = $file->getClientOriginalExtension(); // Extensión del archivo
            $name = trim(base64_encode($imagen).".".$extension);
            $content = file_get_contents($file->getRealPath());
            Storage::disk('images')->put($name, $content);
        }else{
            $name = "no-photo.png";
        }
        $guardado = Producto::create([
            'gtin' => ($request->gtin) ? $request->gtin : null,
            'producto' => strtoupper($request->producto),
            'descripcion' => strtoupper($request->descripcion),
            'precio_detal' => $request->precio_detal,
            'precio_embalaje' => $request->precio_embalaje,
            'costo_detal' => $request->costo_detal,
            'stock_actual' => $request->stock_actual,
            'marca_id' => $request->marca_id,
            'unidades_por_embalaje' => $request->unidades_por_embalaje,
            'categoria_id' => $request->categoria_id,
            'proveedor_id' => $request->proveedor_id,
            'embalaje_id' => $request->embalaje_id,
            'unidad_medida_id' => $request->unidad_medida_id,
            'image'=> $name,
            'active' => ($request->active) ? true : false
        ]);
        if ($guardado) {
            //return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
            return back()->with('success', 'Datos guardados exitosamente!')->with('productoId', $guardado->id);
        } else {
            return back()->withErrors(['error' => 'Hubo un problema al guardar los datos.']);
        }
    }

    public function show($id){
        $producto = Producto::with(['categoria', 'proveedor'])->findOrFail($id);
        return new ProductoResource($producto);
    }

    public function edit(Producto $producto)
    {
        $unidadesMedidas = UnidadMedida::all();
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        $embalajes = Embalaje::all();
        return view('productos.edit', compact('producto','unidadesMedidas','marcas','categorias','proveedores','embalajes'));
    }

    public function update(Request $request, Producto $producto)
    {
        //dd($request->all());
        $request->validate([
            'gtin' => ['nullable','digits_between:8,14','regex:/^\d+$/'],
            'producto' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'precio_detal' => 'required|numeric|min:0|max:999999.99',
            'precio_embalaje' => 'nullable|numeric|min:0|max:999999.99',
            'costo_detal' => 'nullable|numeric|min:0|max:999999.99',
            'stock_actual' => 'required|integer|min:0',
            'marca_id' => 'nullable|exists:marcas,id',
            'unidades_por_embalaje' => 'nullable|integer|min:1',
            'categoria_id' => 'nullable|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'embalaje_id' => 'nullable|exists:embalajes,id',
            'unidad_medida_id' => 'nullable|exists:unidad_medidas,id',
            'image'=>'image|mimes:png,gif,jpeg,jpg|max:3072',
            'active' => 'required|boolean',
        ]);
        if ($request->hasFile('image') && $request->image) {
            $file = $request->file('image');
            $imagen = $file->getClientOriginalName();
            //$type = $file->getClientMimeType(); // Tipo MIME del archivo
            $extension = $file->getClientOriginalExtension(); // Extensión del archivo
            $name = trim(base64_encode($imagen).".".$extension);
            $content = file_get_contents($file->getRealPath());
            if($name !== $producto->image){
                $data['img'][0] = [
                    'subida comprimida' => $name,
                    'almacenada en bd' => $producto->image,
                    'mensaje' => 'Son diferentes'
                ];
                if ((Storage::disk('images')->exists($producto->image))) {
                    if ($producto->image !== 'no-photo.png') {
                        Storage::disk('images')->delete($producto->image);
                        Storage::disk('images')->put($name, $content);
                    }else{
                        Storage::disk('images')->put($name, $content);
                    }
                }else{
                    Storage::disk('images')->put($name, $content);
                }

            }else{
                $name = ($producto->image === "no-photo.png") ? "no-photo.png" : $producto->image;
            }
        }else{
            $name = ($producto->image === "no-photo.png") ? "no-photo.png" : $producto->image;
        }

        $guardado = $producto->update([
            'gtin' => ($request->gtin) ? $request->gtin : null,
            'producto' => strtoupper($request->producto),
            'descripcion' => strtoupper($request->descripcion),
            'precio_detal' => $request->precio_detal,
            'precio_embalaje' => $request->precio_embalaje,
            'costo_detal' => $request->costo_detal,
            'stock_actual' => $request->stock_actual,
            'marca_id' => $request->marca_id,
            'unidades_por_embalaje' => $request->unidades_por_embalaje,
            'categoria_id' => $request->categoria_id,
            'proveedor_id' => $request->proveedor_id,
            'embalaje_id' => $request->embalaje_id,
            'unidad_medida_id' => $request->unidad_medida_id,
            'image'=> $name,
            'active' => ($request->active) ? true : false
        ]);

        if($guardado) {
            return back()->with('success', 'Datos actualizados exitosamente!');
        } else {
            return back()->withErrors(['error' => 'Hubo un problema al actualizar los datos.']);
        }
    }

    public function destroy(Producto $producto)
    {
        if ($producto->image !== 'no-photo.png') {
            Storage::disk('images')->delete($producto->image);
        }
        $producto->delete();
        return redirect()->route('productos.index');
    }

}
