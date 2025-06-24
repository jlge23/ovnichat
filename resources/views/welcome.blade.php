<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/whatsapp.js'])
        @endif
    </head>
    <body class="antialiased">

        {{-- {{$productos}} --}}
        {{-- <meta http-equiv="refresh" content="0; url={{env('URL')}}/login"> --}}
        {{-- <h2 id="msg"></h2> --}}
        {{-- <hr>
        Ollama
        <form method="POST" action="{{route('llama')}}">
            @csrf
            <input type="text" name="prompt" placeholder="Haz tu pregunta">
            <button type="submit">Enviar</button>
        </form>
        <hr>
        @if(isset($texto))
            <div class="alert alert-success">
                {{ $texto }}&nbsp;<br>
            </div>
        @endif
        @if(isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif --}}
    </body>
</html>
