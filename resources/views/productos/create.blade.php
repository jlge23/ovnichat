@extends('layouts.app')

@section('title','Productos')

@vite(['resources/sass/app.scss', 'resources/js/app.js','resources/css/app.css'])
@section('content')
    <div class="container">
        <h1>Agregar Productos</h1>
        <a href="{{route('productos.index')}}">Volver al listado</a>
        <hr>
        <form id="nueva_productos" enctype='multipart/form-data' action="{{route('productos.store')}}" method="POST" class="form-horizontal">
            @csrf
            @method('POST')
            @include('productos.form')
            <br>
            <button type="submit" class="btn btn-success">Registrar</button>
        </form>
        <hr>
    </div>
    @vite(['resources/js/productos.js'])
@endsection
