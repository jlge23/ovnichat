@extends('layouts.app')

@section('title','Productos')

@vite(['resources/css/app.css'])

@section('content')
<div class="container-fluid">
    <h1 class="alert alert-primary">Productos -  Listado.&nbsp;&nbsp;{{-- <a href="{{route('productos.create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar nuevo</a> --}}</h1>
    <table id="DT_productos" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>N°</th>
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
                <th>Imagen</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{$producto->id}}</td>
                    <td>
                        <span {{($producto->gtin)? "class='bg-success'" : "class='bg-danger'"}}>{{($producto->gtin)? $producto->gtin : "No aplica"}}</span>
                    </td>
                    <td>{{$producto->sku}}</td>
                    <td>{{$producto->nombre}}&nbsp;{{$producto->descripcion}}</td>
                    <td>{{$producto->unidadmedida->nombre}}</td>
                    <td>
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item list-group-item-action">Precio detal:&nbsp;{{$producto->precio_detal}}</li>
                            <li class="list-group-item list-group-item-action">Precio por embalaje:&nbsp;{{$producto->precio_embalaje}}</li>
                            <li class="list-group-item list-group-item-action">Costo detal:&nbsp;{{$producto->costo_detal}}</li>
                        </ol>
                    </td>
                    <td>{{$producto->marca->marca}}</td>
                    <td>{{$producto->categoria->nombre}}</td>
                    <td>{{$producto->embalaje->tipo_embalaje}}</td>
                    <td>{{$producto->proveedor->nombre}}</td>
                    <td>{{$producto->stock_actual}}</td>
                    @if (isset($producto->image))
                        <td class="imagen" style="background-image: url('{{ asset('storage/images/' . $producto->image) }}')"></td>
                    @else
                        <td class="imagen" style="background-image: url('{{ asset('storage/images/no-photo.png') }}')"></td>
                    @endif
                    <td>
                        <span {{($producto->active)? "class='bg-success'" : "class='bg-danger'"}}>{{($producto->active)? "Activo" : "Inactivo"}}</span>
                    </td>
                    <td>Acciones</td>
                    {{-- <td>
                        @if (count($producto->valores))
                            <a class="btn btn-primary" type="button" href="productosunidades/?id={{$producto->id}}"><i class="fas fa-wine-glass-alt">&nbsp;[{{count($producto->valores)}}]</i></a>
                        @else
                            <a class="btn btn-primary" type="button" href="productosunidades/?id={{$producto->id}}"><i class="fa fa-plus">&nbsp;Agregar</i></a>
                        @endif
                    </td>
                    <td>
                        @if (count($producto->images))
                            <a class="btn btn-info" type="button" href="imagenesproductos/?id={{$producto->id}}"><i class="fas fa-images">&nbsp;[{{count($producto->images)}}]</i></a>
                        @else
                            <a class="btn btn-info" type="button" href="imagenesproductos/?id={{$producto->id}}"><i class="fa fa-plus">&nbsp;Agregar</i></a>
                        @endif
                    </td>
                    <td>
                        <form action="{{route('productos.destroy',$producto->id)}}" method="POST" id="FORM_producto_delete">
                            @csrf
                            @method('DELETE')
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary ver-detalles" data-id="{{$producto->id}}">Información</button>
                                <a href="{{route('productos.edit',$producto->id)}}" class="btn btn-sm btn-secondary">Editar</a>
                                <button type="button" class="btn btn-sm btn-warning">Eliminar</button>&nbsp;
                            </div>
                        </form>
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>N°</th>
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
                <th>Imagen</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </tfoot>
    </table>
</div>
@vite(['resources/js/productos.js'])
@endsection
