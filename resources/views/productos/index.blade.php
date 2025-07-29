@extends('layouts.app')

@section('title','Productos')

@vite(['resources/css/app.css'])

@section('content')
<div class="container">
    <h1 class="alert alert-primary">Productos -  Listado.&nbsp;&nbsp;<a href="{{route('productos.create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar nuevo</a></h1>
    <table id="DT_productos" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Acciones</th>
                <th>Imagen</th>
                <th>Codigo GTIN</th>
                <th>Codigo SKU</th>
                <th>Nombre y Descripción</th>
                <th>Unidad de media</th>
                <th>Precios</th>
                <th>Marca</th>
                <th>Categoría</th>
                <th>Embalaje</th>
                <th>Proveedor</th>
                <th>Stock actual</th>
                <th>Estatus</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>
                        <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" id="FORM_producto_delete">
                            @csrf
                            @method('DELETE')
                            <div class="btn-group" role="group">
                                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-secondary">Editar</a>
                                <button type="submit" class="btn btn-sm btn-warning">Eliminar</button>&nbsp;
                            </div>
                        </form>
                    </td>
                    @if (isset($producto->image))
                        <td class="imagen" style="background-image: url('{{ asset('storage/images/' . $producto->image) }}')"></td>
                    @else
                        <td class="imagen" style="background-image: url('{{ asset('storage/images/no-photo.png') }}')"></td>
                    @endif
                    <td>
                        <span {{($producto->gtin)? "class='bg-success'" : "class='bg-danger'"}}>{{($producto->gtin)? $producto->gtin : "No aplica"}}</span>
                    </td>
                    <td>{{$producto->sku}}</td>
                    <td>{{$producto->producto}}&nbsp;{{$producto->descripcion}}</td>
                    <td>{{$producto->unidadmedida->unidad}}</td>
                    <td>
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item list-group-item-action">Precio detal:&nbsp;<b>{{$producto->precio_detal}}</b></li>
                            <li class="list-group-item list-group-item-action">Precio por embalaje:&nbsp;<b>{{$producto->precio_embalaje}}</b></li>
                            <li class="list-group-item list-group-item-action">Costo detal:&nbsp;<b>{{$producto->costo_detal}}</b></li>
                        </ol>
                    </td>
                    <td>{{$producto->marca->marca}}</td>
                    <td>{{$producto->categoria->categoria}}</td>
                    <td>{{$producto->embalaje->embalaje}}</td>
                    <td>{{$producto->proveedor->proveedor}}</td>
                    <td>{{$producto->stock_actual}}</td>
                    <td>
                        <p {{($producto->active = 'Activo')? "class='text-success'" : "class='text-danger'"}}>{{($producto->active)? "Activo" : "Inactivo"}}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Acciones</th>
                <th>Imagen</th>
                <th>Codigo GTIN</th>
                <th>Codigo SKU</th>
                <th>Nombre y Descripción</th>
                <th>Unidad de media</th>
                <th>Precios</th>
                <th>Marca</th>
                <th>Categoría</th>
                <th>Embalaje</th>
                <th>Proveedor</th>
                <th>Stock actual</th>
                <th>Estatus</th>
            </tr>
        </tfoot>
    </table>

</div>
@vite(['resources/js/productos.js'])
@endsection
