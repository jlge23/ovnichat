@extends('layouts.app')
@section('title','Chatbot Builder')
@section('content')

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Chatbot Builder</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            canvas {
                border: 1px solid #aaa;
                margin: 30px auto;
                display: block;
            }
        </style>
    </head>
    <body>
        <div class="container text-center">
            <h1>🛠️ Chatbot Builder con Canvas PROTOTIPO - SOLO PARA PRUEBA DE LO QUE SE NECESITA</h1>
            <div id="nodeToolbar" class="d-flex gap-2 p-2 flex-wrap" style="background:#f5f5f5; border-bottom:1px solid #ccc;">
                <!-- Cada botón representa un tipo de mensaje WhatsApp -->
                <button class="btn btn-sm btn-outline-primary node-button" data-type="text" title="Mensaje de texto">💬 Texto</button>
                <button class="btn btn-sm btn-outline-primary node-button" data-type="image" title="Imagen">🖼️ Imagen</button>
                <button class="btn btn-sm btn-outline-primary node-button" data-type="video" title="Video">📹 Video</button>
                <button class="btn btn-sm btn-outline-primary node-button" data-type="audio" title="Audio">🔊 Audio</button>
                <button class="btn btn-sm btn-outline-primary node-button" data-type="document" title="Documento">📄 Documento</button>
                <button class="btn btn-sm btn-outline-primary node-button" data-type="buttons" title="Botones">🎛️ Botones</button>
                <button class="btn btn-sm btn-outline-primary node-button" data-type="list" title="Lista">📋 Lista</button>
                <button class="btn btn-sm btn-outline-primary node-button" data-type="location" title="Ubicación">📍 Ubicación</button>
                <button class="btn btn-sm btn-outline-primary node-button" data-type="contact" title="Contacto">👤 Contacto</button>
                <button id="exportFlowBtn" class="btn btn-outline-primary">📤 Exportar JSON</button>
            </div>
            <canvas id="builderCanvas" width="1200" height="600"></canvas>
        </div>
        <div class="modal fade" id="nodeModal" tabindex="-1" aria-labelledby="nodeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" id="editNodeForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nodeModalLabel">Editar Nodo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="nodeId">
                        <div class="mb-3">
                            <label for="nodeText" class="form-label">Texto</label>
                            <input type="text" class="form-control" id="nodeText">
                        </div>
                        <div id="buttonsConfigSection" style="display: none;">
                            <label><strong>Opciones del Botón:</strong></label>
                            <div id="buttonOptionsContainer">
                                <!-- JS generará campos aquí -->
                            </div>
                            <button type="button" class="btn btn-sm btn-success mt-2" id="addButtonOption">➕ Agregar opción</button>
                        </div>
                        <div id="listConfigSection" style="display: none;">
                            <label><strong>Ítems de la Lista:</strong></label>
                            <div id="listOptionsContainer">
                                <!-- JS generará campos aquí -->
                            </div>
                            <button type="button" class="btn btn-sm btn-success mt-2" id="addListItem">➕ Agregar ítem</button>
                        </div>
                        <div id="imageConfigSection" style="display: none;">
                            <label for="imageUrl" class="form-label">URL pública de la imagen</label>
                            <input type="text" class="form-control mb-2" id="imageUrl" placeholder="https://ejemplo.com/imagen.jpg">

                            <label for="imageCaption" class="form-label">Texto descriptivo (opcional)</label>
                            <input type="text" class="form-control" id="imageCaption" placeholder="Catálogo Primavera">
                        </div>
                        <div id="documentConfigSection" style="display: none;">
                            <label for="documentUrl" class="form-label">URL pública del documento</label>
                            <input type="text" class="form-control mb-2" id="documentUrl" placeholder="https://ejemplo.com/factura.pdf">

                            <label for="documentFilename" class="form-label">Nombre del archivo</label>
                            <input type="text" class="form-control" id="documentFilename" placeholder="Factura_2025.pdf">
                        </div>
                        <div id="audioConfigSection" style="display: none;">
                            <label for="audioUrl" class="form-label">URL pública del archivo de audio</label>
                            <input type="text" class="form-control" id="audioUrl" placeholder="https://ejemplo.com/audio.mp3">
                        </div>
                        <div id="contactConfigSection" style="display: none;">
                            <label for="contactName" class="form-label">Nombre de contacto</label>
                            <input type="text" class="form-control mb-2" id="contactName" placeholder="Ej: Soporte Técnico">

                            <label for="contactPhone" class="form-label">Número de teléfono</label>
                            <input type="text" class="form-control" id="contactPhone" placeholder="+593912345678">
                        </div>
                        <div id="locationConfigSection" style="display: none;">
                            <label class="form-label">Nombre del lugar</label>
                            <input type="text" class="form-control mb-2" id="locationName" placeholder="Ej: Sucursal Norte">

                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control mb-2" id="locationAddress" placeholder="Ej: Av. Principal y Calle 7">

                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Latitud</label>
                                    <input type="number" class="form-control" id="locationLat" placeholder="-2.165">
                                </div>
                                <div class="col">
                                    <label class="form-label">Longitud</label>
                                    <input type="number" class="form-control" id="locationLng" placeholder="-79.891">
                                </div>
                            </div>
                        </div>
                        <!-- Más campos opcionales aquí -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    </html>

@vite(['resources/js/ChatbotBuilder.js'])
@endsection
