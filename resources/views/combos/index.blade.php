@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="alert alert-primary">Combos
        <small>
            <button id="btnNuevoCombo" class="btn btn-primary mb-2">Agregar Combo</button>
        </small>
    </h2>
    @if (session('success'))
        <div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="NuevoCombo" style="display: none;">
        <form class="form-control," action="{{route('combos.store')}}" method="post" id="FRM_NuevoCombo">
            @csrf
            @method('POST')
            <div class="input-group">
                <span class="input-group-text">Nombre del combo</span>
                <input class="form-control form-control-lg" type="text" placeholder="Combo Navideño" id="combo" name="combo" required>

            </div>
            <div class="input-group">
                <span class="input-group-text">Descripción del combo</span>
                <textarea class="form-control" type="text" placeholder="Regala algo en navidad" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="input-group">
                <span class="input-group-text">Precio del combo</span>
                <input class="form-control form-control-sm" type="number" step="0.01" placeholder="0.00" id="precio" name="precio" required>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" for="status">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option selected>Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="agotado">Agotado</option>
                </select>
            </div>
            <button class="btn btn-outline-success" type="submit">Agregar</button>
        </form>
    </div>
    <hr>
    <table class="table table-striped table-bordered table-hover" id="DT_combos">
        <thead>
            <tr>
                <th>N°</th>
                <th>Combo</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Status</th>
                <th>Productos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($combos as $combo)
                <tr>
                    <td>{{$combo->id}}</td>
                    <td>{{$combo->combo}}</td>
                    <td>{{$combo->descripcion}}</td>
                    <td>{{$combo->precio}}</td>
                    <td>{{$combo->status}}</td>
                    <td>
                        @if ($combo->productos->isNotEmpty())
                            <ol class="list-group list-group-numbered">
                                @foreach ($combo->productos as $producto)
                                    <li class="list-group-item list-group-item-action"><small>{{$producto->producto}}&nbsp;{{$producto->descripcion}}&nbsp;[{{$producto->pivot->cantidad}}]</small></li>
                                @endforeach
                            </ol>
                            <small id="edit{{$combo->id}}"><b class="btn btn-warning text text-primary ver_productos">haga click para cambiar</b></small>
                        @else
                            <small id="edit{{$combo->id}}"><b class="btn btn-info text text-secondary ver_productos">haga click para asociar productos</b></small>
                        @endif
                    </td>
                    <td>
                        <form action="{{route('combos.destroy',$combo->id)}}" method="POST" id="FORM_combo_delete">
                            @csrf
                            @method('DELETE')
                            <div class="btn-group" role="group">
                                <a class="btn btn-sm btn-warning" href="combos/{{$combo->id}}/edit">Editar</a>
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>&nbsp;
                            </div>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <th>N°</th>
            <th>Combo</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Status</th>
            <th>Productos</th>
            <th>Acciones</th>
        </tfoot>
    </table>
</div>

{{-- Modal --}}
<div class="modal fade" id="modalProductos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalTitle">Título del Modal</h5>&nbsp;
                <button type="button" class="close MD_cerrar" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-control" id="FRM_asociar" action="" method="post">
                    @csrf
                    @method('PUT')
                    <table class="table table-striped table-bordered table-hover" id="DT_ProdAsoc">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Stock actual</th>
                                <th>Asignado</th>
                                <th>Cantidad asignada</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>N°</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Stock actual</th>
                                <th>Asignado</th>
                                <th>Cantidad asignada</th>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary MD_cerrar" data-dismiss="modal">Cerrar</button>
                <button form="FRM_asociar" type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
{{-- Fin Modal --}}
@vite(['resources/js/combos.js'])
@endsection
