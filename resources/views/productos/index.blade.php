@extends('layouts.app')

@section('title','Productos')

@vite(['resources/css/app.css'])

@section('content')
<div class="container-fluid">
    <h1 class="alert alert-primary">Productos -  Listado.&nbsp;&nbsp;<a href="{{route('productos.create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar nuevo</a></h1>
    <table id="DT_productos" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>N°</th>
                <th>Imagen</th>
                <th>Codigo GS1</th>
                <th>Descripción</th>
                <th>Marca</th>
                <th>$ C/U</th>
                <th>Unidades</th>
                <th>$ Mayor (X Embalaje)</th>
                <th>Medidas</th>
                <th>Imágenes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{$producto->id}}</td>
                    @if (isset($producto->images->first()->path))
                        <td class="imagen" style="background-image: url('{{ asset('storage/images/' . $producto->images->first()->path) }}')"></td>
                    @else
                        <td class="imagen" style="background-image: url('{{ asset('storage/images/no-photo.png') }}')"></td>
                    @endif
                    <td>{{$producto->codigo_gs1}}</td>
                    @if (isset($producto->valores->first()->pivot->valor))
                        <td>{{$producto->descripcion}}&nbsp;{{$producto->valores->first()->pivot->valor.$producto->valores->first()->simbolo}}&nbsp;</td>
                    @else
                        <td>{{$producto->descripcion}}</td>
                    @endif
                    @if (isset($producto->marca->marca))
                        <td>{{$producto->marca->marca}}</td>
                    @else
                        <td><label class="danger">SIN MARCA ASIGNADA</label></td>
                    @endif
                    <td>${{$producto->precio_detal}}</td>
                    <td>{{$producto->unidades_por_embalaje}}</td>
                    <td>${{$producto->precio_mayor}}</td>
                    <td>
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
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>N°</th>
                <th>Imagen</th>
                <th>Codigo GS1</th>
                <th>Descripción</th>
                <th>Marca</th>
                <th>$ C/U</th>
                <th>Unidades</th>
                <th>$ Mayor (X Embalaje)</th>
                <th>Medidas</th>
                <th>Imágenes</th>
                <th>Acciones</th>
            </tr>
        </tfoot>
    </table>
</div>
{{-- Inicio Modal --}}
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesLabel"></h5>&nbsp;&nbsp;
                <button type="button" id="modalClose" class="btn btn-dark"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="modalDetallesBody">
                <form id="detalles" class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <b>Descripcion:</b>&nbsp;<input class="form-control" type="text" id="descr" value="" readonly>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <b>Marca:</b>&nbsp;<input class="form-control" type="text" id="marca" value="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-lg-3">
                            <b>Código GTIN:</b>&nbsp;<input class="form-control" type="text" id="gtin" value="" readonly>
                        </div>
                        <div class="col-md-3 col-sm-3 col-lg-3">
                            <b>SKU:</b>&nbsp;<input class="form-control" type="text" id="sku" value="" readonly>
                        </div>
                        <div class="col-md-3 col-sm-3 col-lg-3">
                            <b>SENCAMER:</b>&nbsp;<input class="form-control" type="text" id="sencamer" value="" readonly>
                        </div>
                        <div class="col-md-3 col-sm-3 col-lg-3">
                            <b>MPPS:</b>&nbsp;<input class="form-control" type="text" id="mpps" value="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <b>Procedencia:</b>&nbsp;<input class="form-control" type="text" id="importado" value="" readonly>
                        </div>
                        <div class="col-md-4 col-sm-34 col-lg-4">
                            <b>Propiedad del producto:</b>&nbsp;<input class="form-control" type="text" id="propiedad" value="" readonly>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <b>Estado físico</b>&nbsp;<input class="form-control" type="text" id="fisico" value="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <b>Precio detal:</b>&nbsp;<input class="form-control" type="text" id="detal" value="" readonly>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <b>Unidades por Embalaje:</b>&nbsp;<input class="form-control" type="text" id="und" value="" readonly>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <b>Precio al mayor:</b>&nbsp;<input class="form-control" type="text" id="mayor" value="" readonly>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <img src="" id="img" class="rounded img-thumbnail" alt="...">
                    </div>
                    <div class="col-md-8 col-sm-8 col-lg-8">
                        <table id="DT_ProductosUnidades" class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Unidad de medida</th>
                                    <th>Valor</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>N°</th>
                                    <th>Unidad de medida</th>
                                    <th>Valor</th>
                                    <th>Descripción</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
         <div class="modal-footer">
            <a id="edit" class="btn btn-sm btn-secondary" href="">Editar</a>
         </div>
       </div>
    </div>
</div>
{{-- Fin Modal --}}

<div class="modal fade" id="modalMarcas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Título del Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Contenido del modal.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

@vite(['resources/js/productos.js'])
@endsection
