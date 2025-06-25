@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="alert alert-primary">Combos</h2>
    <table class="table table-striped table-bordered table-hover" id="DT_combos">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre</th>
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
                    <td>{{$combo->nombre}}</td>
                    <td>{{$combo->descripcion}}</td>
                    <td>{{$combo->precio}}</td>
                    <td>{{$combo->status}}</td>
                    <td>
                        @foreach ($combo->productos as $producto)
                            <label class="label labeb-secondary">{{$producto->nombre}}[{{$producto->pivot->cantidad}}</label>]
                        @endforeach
                    </td>
                    <td>
                        <a href="combos/{{$combo->id}}/edit">Editar</a>
                        <a href="">Eliminar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <th>N°</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Status</th>
            <th>Productos</th>
            <th>Acciones</th>
        </tfoot>
    </table>
</div>
@vite(['resources/js/combos.js'])
@endsection
