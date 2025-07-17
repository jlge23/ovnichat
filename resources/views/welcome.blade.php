@extends('layouts.app')

@section('title','Welcome')

@section('content')
@vite(['resources/css/app.css'])
<div class="container">
    <hr>
    <form class="form-horizontal" method="POST" action="{{ route('llama') }}">
        @csrf
        <div class="input-group mb-3">
            <span class="input-group-text">Pregunta a la Ollama IA</span>
            <input class="form-control" type="text" name="prompt" placeholder="Haz tu pregunta" required>
            <button class="btn btn-dark" type="submit">Enviar</button>
            <span class="input-group-text text-dark">debe tener el modelo: nomic-embed-text:v1.5</span>
        </div>
    </form>

    <hr>

    @if(isset($comparaciones) && count($comparaciones) > 0)
        <h4>🔍 Textos similares encontrados:</h4>
        <table class="table table-striped" id="DT_embeddings">
            <thead>
                <tr>
                    <th>Texto guardado</th>
                    <th>Similitud semántica</th>
                    <th>Intent</th>
                    <th>Entities</th>
                </tr>
            </thead>
            <tbody>
                @foreach(collect($comparaciones)->sortByDesc('similitud') as $item)
                    <tr>
                        <td>{{ $item['texto'] }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar
                                    @if($item['similitud'] > 0.8) bg-success
                                    @elseif($item['similitud'] > 0.5) bg-warning
                                    @else bg-danger
                                    @endif"
                                    role="progressbar" style="width: {{ $item['similitud'] * 100 }}%;">
                                    {{ $item['similitud'] * 100 }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            @if(isset($item['intent']))
                                <span class="badge bg-primary">{{ $item['intent'] }}</span>
                            @else
                                <em class="text-muted">Sin intentos</em>
                            @endif
                        </td>
                        <td>
                            @if(isset($item['entities']) && count($item['entities']) > 0)
                                @foreach($item['entities'] as $entidad)
                                    <span class="badge bg-info">{{ $entidad }}</span>
                                @endforeach
                            @else
                                <em class="text-muted">Sin entidades</em>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">No se encontraron comparaciones disponibles.</div>
    @endif

    @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif
</div>
@vite(['resources/js/welcome.js'])
@endsection
