@extends('layouts.app')

@section('title','Welcome')

@section('content')
@vite(['resources/css/app.css'])
<div class="container-fluid">
    <hr>
    <form class="form-horizontal" method="POST" action="{{ route('llama') }}">
        @csrf
        @method('POST')
        <div class="input-group mb-3">
            <span class="input-group-text">Registrar frase</span>
            <input class="form-control" type="text" name="prompt" placeholder="Frase" required>
            <button class="btn btn-secondary" type="submit">Enviar</button>
            <span class="input-group-text">nomic-embed-text:v1.5</span>
        </div>
    </form>

    <hr>

    @if(isset($comparaciones) && count($comparaciones) > 0 )
        <h4>🔍 Textos similares encontrados:</h4>
        <table class="table table-striped" id="DT_embeddings">
            <thead>
                <tr>
                    <th>Texto guardado</th>
                    <th>Similitud semántica</th>
                    <th>Intent</th>
                    <th>Entities</th>
                    <th>Acciones</th>
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
                                <form method="POST" action="{{ route('asignarIntent', ['embedding' => $item['embeddingId']]) }}">
                                    @csrf
                                    @method('post')
                                    <div class="input-group mb-3">
                                        <select name="intent_id" class="form-select form-select-sm">
                                            <option value="" disabled selected>Selecciona un intent</option>
                                            @foreach($intents as $intent)
                                                <option value="{{ $intent->id }}"
                                                    @if(isset($item['intent']) && $item['intent'] == $intent->intent) selected @endif>
                                                    {{ $intent->intent }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-outline-success" type="submit">Asignar</button>
                                    </div>
                                </form>
                            @else
                                <form method="POST" action="{{ route('asignarIntent', ['embedding' => $item['embeddingId']]) }}">
                                    @csrf
                                    @method('post')
                                    <div class="input-group mb-3">
                                        <select name="intent_id" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Sin Intent</option>
                                            @foreach($intents as $intent)
                                                <option value="{{ $intent->id }}">{{ $intent->intent }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-outline-success" type="submit">Asignar</button>
                                    </div>
                                </form>
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
                        <td>
                            <form action="{{ route('destroy', ['embedding' => $item['embeddingId']]) }}" method="POST" id="FRM_Embedding">
                                @csrf
                                @method('DELETE')
                                <div class="btn-group" role="group">
                                    <button type="submit" class="btn btn-sm btn-warning">Eliminar</button>&nbsp;
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">No se encontraron comparaciones disponibles.</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $mensaje)
                    <li>{{ $mensaje }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@vite(['resources/js/welcome.js'])
@endsection
