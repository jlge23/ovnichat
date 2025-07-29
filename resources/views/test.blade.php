@extends('layouts.app')

@section('title','Test')

@section('content')
@vite(['resources/css/app.css'])
<div class="container">
    <hr>
    <form class="form-horizontal" method="POST" action="{{ route('testia') }}">
        @csrf
        @method('POST')
        <div class="input-group mb-3">
            <span class="input-group-text">Pregunta a la Ollama IA</span>
            <input class="form-control" type="text" name="msg" placeholder="Haz tu pregunta" required autofocus>
            <button class="btn btn-secondary" type="submit">Enviar</button>
        </div>
    </form>

    <hr>

    @isset($output)
        {{$output}}
    @endisset

</div>
@endsection
