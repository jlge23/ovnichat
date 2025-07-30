<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource;
use App\Models\Categoria;
use App\Models\Embalaje;
use App\Models\Marca;
use Illuminate\Http\Request;
use App\Models\Producto;
use Inertia\Inertia;
use App\Models\Proveedor;
use App\Models\UnidadMedida;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with(['categoria', 'proveedor', 'embalaje'])
            ->orderBy('nombre', 'asc')
            ->paginate(15);

        if ($request->has('page')) {
            return response()->json([
                'productos' => $productos,
            ]);
        }

        return Inertia::render("Products/Products", [
            "productos" => $productos,
        ]);
    }

    public function create()
    {
        $unidadesMedidas = UnidadMedida::all();
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        $embalajes = Embalaje::all();
        return response()->json([
            'unidadesMedidas' => $unidadesMedidas,
            'marcas' => $marcas,
            'categorias' => $categorias,
            'proveedores' => $proveedores,
            'embalajes' => $embalajes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'gtin' => ['nullable', 'digits_between:8,14', 'regex:/^\d+$/'],
                'nombre' => 'required|string|max:255',
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
                'image' => 'nullable|image|mimes:png,gif,jpeg,jpg|max:3072',
                'active' => 'required|boolean',
            ],
            [
                'gtin.digits_between' => 'El código de barra debe tener entre :min y :max dígitos.',
                'gtin.regex' => 'El código de barra solo debe contener números.',

                'nombre.required' => 'El nombre del producto es obligatorio.',
                'nombre.max' => 'El nombre no debe exceder :max caracteres.',

                'descripcion.max' => 'La descripción no debe exceder :max caracteres.',

                'precio_detal.required' => 'El precio detal es obligatorio.',
                'precio_detal.numeric' => 'El precio detal debe ser un número.',
                'precio_detal.min' => 'El precio detal no puede ser menor que :min.',
                'precio_detal.max' => 'El precio detal no debe ser mayor que :max.',

                'precio_embalaje.numeric' => 'El precio por embalaje debe ser un número.',
                'precio_embalaje.min' => 'El precio por embalaje no puede ser menor que :min.',
                'precio_embalaje.max' => 'El precio por embalaje no debe ser mayor que :max.',

                'costo_detal.numeric' => 'El costo detal debe ser un número.',
                'costo_detal.min' => 'El costo detal no puede ser menor que :min.',
                'costo_detal.max' => 'El costo detal no debe ser mayor que :max.',

                'stock_actual.required' => 'El stock actual es obligatorio.',
                'stock_actual.integer' => 'El stock actual debe ser un número entero.',
                'stock_actual.min' => 'El stock actual no puede ser menor que :min.',

                'unidades_por_embalaje.integer' => 'Las unidades por embalaje deben ser un número entero.',
                'unidades_por_embalaje.min' => 'Las unidades por embalaje deben ser al menos :min.',

                'marca_id.exists' => 'La marca seleccionada no existe.',
                'categoria_id.exists' => 'La categoría seleccionada no existe.',
                'proveedor_id.exists' => 'El proveedor seleccionado no existe.',
                'embalaje_id.exists' => 'El embalaje seleccionado no existe.',
                'unidad_medida_id.exists' => 'La unidad de medida seleccionada no existe.',

                'image.image' => 'El archivo debe ser una imagen válida.',
                'image.mimes' => 'La imagen debe ser de tipo: png, gif, jpeg o jpg.',
                'image.max' => 'La imagen no debe pesar más de :max kilobytes.',

                'active.required' => 'El estatus del producto es obligatorio.',
                'active.boolean' => 'El estatus debe ser verdadero o falso.',
            ]
        );

        if ($request->hasFile('image') && $request->image) {
            $file = $request->file('image');
            $imagen = $file->getClientOriginalName();
            //$type = $file->getClientMimeType(); // Tipo MIME del archivo
            $extension = $file->getClientOriginalExtension(); // Extensión del archivo
            $name = trim(base64_encode($imagen) . "." . $extension);
            $content = file_get_contents($file->getRealPath());
            Storage::disk('images')->put($name, $content);
        } else {
            $name = null;
        }
        $guardado = Producto::create([
            'gtin' => ($request->gtin) ? $request->gtin : null,
            'nombre' => Str::upper($request->nombre),
            'descripcion' => Str::ucfirst(Str::lower($request->descripcion)),
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
            'image' => $name,
            'active' => ($request->active) ? true : false
        ]);
        if ($guardado) {
            //return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
            return back()->with('success', 'Datos guardados exitosamente!')->with('productoId', $guardado->id);
        } else {
            return back()->withErrors(['error' => 'Hubo un problema al guardar los datos.']);
        }
    }

    public function show($id)
    {
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
        return view('productos.edit', compact('producto', 'unidadesMedidas', 'marcas', 'categorias', 'proveedores', 'embalajes'));
    }

    public function update(Request $request, Producto $producto)
    {
        //dd($request->all());
        $request->validate([
            'gtin' => ['nullable', 'digits_between:8,14', 'regex:/^\d+$/'],
            'nombre' => 'required|string|max:255',
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
            'image' => 'nullable|image|mimes:png,gif,jpeg,jpg|max:3072',
            'active' => 'required|boolean',
        ]);
        if ($request->hasFile('image') && $request->image) {
            $file = $request->file('image');
            $imagen = $file->getClientOriginalName();
            //$type = $file->getClientMimeType(); // Tipo MIME del archivo
            $extension = $file->getClientOriginalExtension(); // Extensión del archivo
            $name = trim(base64_encode($imagen) . "." . $extension);
            $content = file_get_contents($file->getRealPath());
            if ($name !== $producto->image) {
                $data['img'][0] = [
                    'subida comprimida' => $name,
                    'almacenada en bd' => $producto->image,
                    'mensaje' => 'Son diferentes'
                ];
                if ((Storage::disk('images')->exists($producto->image))) {
                    if ($producto->image !== 'no-photo.png') {
                        Storage::disk('images')->delete($producto->image);
                        Storage::disk('images')->put($name, $content);
                    } else {
                        Storage::disk('images')->put($name, $content);
                    }
                } else {
                    Storage::disk('images')->put($name, $content);
                }
            } else {
                $name = ($producto->image === "no-photo.png") ? "no-photo.png" : $producto->image;
            }
        } else {
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
            'image' => $name,
            'active' => ($request->active) ? true : false
        ]);

        if ($guardado) {
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
