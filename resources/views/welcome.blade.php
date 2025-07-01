@extends('layouts.app')

@section('title','Welcome')

@section('content')
@vite(['resources/css/app.css'])
<div class="container">
    {{-- <meta http-equiv="refresh" content="0; url={{env('URL')}}/login"> --}}
    {{-- <h2 id="msg"></h2> --}}
    <hr>
    <form class="form-horizontal" method="POST" action="{{route('llama')}}">
        @csrf
        <div class="input-group mb-3">
            <span class="input-group-text">Pregunta a la Ollama IA</span>
            <input class="form-control" type="text" name="prompt" placeholder="Haz tu pregunta" required>
            <span class="input-group-text">Modelo de IA</span>
            <select name="model" id="model" class="form-select">
                @isset($modelos)
                    @for ($i=0;$i < count($modelos);$i++)
                        <option value="{{ $modelos[$i] }}">{{ $modelos[$i] }}</option>
                    @endfor
                @endisset
            </select>
            <button class="btn btn-dark" type="submit">Enviar</button>
        </div>
    </form>
    <hr>
    @if(isset($r))
        <div class="alert alert-success">
            {{ $r }}&nbsp;<br>
        </div>
    @endif
    @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif
</div>
@endsection
