@extends('layouts.app')

@section('title','Test')

@section('content')
@vite(['resources/css/app.css'])
<div class="container">
    <hr>
    <form class="form-horizontal" method="POST" action="{{ route('testia') }}">
        @csrf
        <div class="input-group mb-3">
            <span class="input-group-text">Pregunta a la Ollama IA</span>
            <input class="form-control" type="text" name="msg" placeholder="Haz tu pregunta" required autofocus>

{{--             <span class="input-group-text">Modelo de IA</span>
            <select name="model" id="model" class="form-select">
                @isset($modelos)
                    @foreach($modelos as $modelo)
                        @if ((str_contains($modelo, 'embed')))
                            <option value="{{ $modelo }}" selected>{{ $modelo }}</option>
                        @else
                            <option value="{{ $modelo }}">{{ $modelo }}</option>
                        @endif
                    @endforeach
                @endisset
            </select> --}}

            <button class="btn btn-dark" type="submit">Enviar</button>
        </div>
    </form>

    <hr>

    @isset($output)
        {{$output}}
    @endisset

</div>
@endsection
