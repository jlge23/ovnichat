@extends('layouts.app')

@section('title','Productos')

@vite(['resources/sass/app.scss', 'resources/js/app.js','resources/css/app.css'])
@section('content')
    <div class="container">
        <h1>Editar datos - Productos</h1>
        <a href="{{route('productos.index')}}">Volver al listado</a>
        <hr>
        <h3>SKU:&nbsp;{{$producto->sku}}</h3>
        <form id="editar_productos" enctype='multipart/form-data' action="{{route('productos.update',$producto->id)}}" method="POST" class="form-horizontal">
            @csrf
            @method('put')
            @include('productos.form')
            <br>
            <input type="hidden" name="id" value="{{$producto->id}}">
            <button type="submit" class="btn btn-success">Actualizar</button>
        </form>
    </div>
    @vite(['resources/js/productos.js'])
@endsection
