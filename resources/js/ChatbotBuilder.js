import './app';
import $ from 'jquery';
import Modal from 'bootstrap/js/dist/modal';
import Swal from 'sweetalert2'

$(function () {

    const canvas = $('#builderCanvas')[0];
    const ctx = canvas.getContext('2d');

    class Node {
        constructor(id, x, y, text, type = 'text', config = {}) {
            this.id = id;
            this.x = x;
            this.y = y;
            this.text = text;
            this.type = type;
            this.width = 180;
            this.height = 100;
            this.config = config;
            this.loadedImage = null; // 🖼️ vista previa
        }

        center() {
            return {
                x: this.x + this.width / 2,
                y: this.y + this.height
            };
        }

        getOutputConnector() {
            return {
                x: this.x + this.width - 10,
                y: this.y + this.height - 20
            };
        }

        getInputConnector() {
            return {
                x: this.x + 10,
                y: this.y + this.height - 20
            };
        }

        getButtonConnectorIndex(mx, my) {
        if (this.type !== "buttons" || !this.config.buttons) return null;
            return this.config.buttons.slice(0, 3).findIndex((btn, i) => {
                const cx = this.x + this.width - 10;
                const cy = this.y + 40 + i * 20;
                return Math.hypot(mx - cx, my - cy) <= 8;
            });
        }

        draw(ctx, isConnectedIn, isConnectedOut) {
            if (this.type === "image") {
                if (this.config.url && !this.loadedImage) {
                    this.loadedImage = new Image();
                    this.loadedImage.src = this.config.url;
                    this.loadedImage.onload = () => render();
                    this.loadedImage.onerror = () => { this.loadedImage = null; };
                }
            }

            if (this.type === "list") {
                const itemCount = this.config.items ? this.config.items.length : 0;
                ctx.fillStyle = "#fff";
                ctx.strokeStyle = "#444";
                ctx.fillRect(this.x, this.y, this.width, this.height);
                ctx.strokeRect(this.x, this.y, this.width, this.height);

                ctx.fillStyle = "#222";
                ctx.font = "14px sans-serif";
                ctx.fillText(this.text, this.x + 10, this.y + 25);
                ctx.fillText(`Tipo: ${this.type}`, this.x + 10, this.y + 45);
                ctx.fillText(`Ítems: ${itemCount}`, this.x + 10, this.y + 65);

                // Entrada
                const inPos = this.getInputConnector();
                ctx.beginPath();
                ctx.arc(inPos.x, inPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedIn ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Único conector de salida
                const outPos = this.getOutputConnector();
                ctx.beginPath();
                ctx.arc(outPos.x, outPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedOut ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Botón editar
                ctx.fillStyle = "#00bcd4";
                ctx.fillRect(this.x + this.width - 70, this.y + this.height - 30, 60, 20);
                ctx.fillStyle = "#fff";
                ctx.font = "12px sans-serif";
                ctx.fillText("Editar", this.x + this.width - 62, this.y + this.height - 15);

                // Botón eliminar
                if (this.id !== "start") {
                    ctx.fillStyle = "#f44336";
                    ctx.fillRect(this.x + this.width - 25, this.y + 5, 20, 20);
                    ctx.fillStyle = "#fff";
                    ctx.font = "bold 14px sans-serif";
                    ctx.fillText("✕", this.x + this.width - 21, this.y + 20);
                }
                return; // evita redibujar el nodo como tipo genérico
            }

            if (this.type === "image") {
                if (this.config.url && !this.loadedImage) {
                    this.loadedImage = new Image();
                    this.loadedImage.src = this.config.url;
                    this.loadedImage.onload = () => render(); // 🔁 Redibuja cuando cargue
                    this.loadedImage.onerror = () => {
                        console.warn("No se pudo cargar la imagen en nodo:", this.id);
                        this.loadedImage = null;
                    };
                }
            }
            if (this.type === "image") {
                const caption = this.config.caption || "";
                ctx.fillStyle = "#fff";
                ctx.strokeStyle = "#444";
                ctx.fillRect(this.x, this.y, this.width, this.height);
                ctx.strokeRect(this.x, this.y, this.width, this.height);

                // Texto principal
                ctx.fillStyle = "#222";
                ctx.font = "13px sans-serif";
                ctx.fillText(this.text, this.x + 10, this.y + 25);
                ctx.fillText(`Tipo: ${this.type}`, this.x + 10, this.y + 45);
                ctx.fillText(`Caption: ${caption}`, this.x + 10, this.y + 65);

                // Imagen previsualizada
                if (this.loadedImage) {
                    ctx.drawImage(this.loadedImage, this.x + this.width - 70, this.y + 10, 60, 60);
                }

                // Entrada
                const inPos = this.getInputConnector();
                ctx.beginPath();
                ctx.arc(inPos.x, inPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedIn ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Salida
                const outPos = this.getOutputConnector();
                ctx.beginPath();
                ctx.arc(outPos.x, outPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedOut ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Botón editar
                ctx.fillStyle = "#00bcd4";
                ctx.fillRect(this.x + this.width - 70, this.y + this.height - 30, 60, 20);
                ctx.fillStyle = "#fff";
                ctx.font = "12px sans-serif";
                ctx.fillText("Editar", this.x + this.width - 62, this.y + this.height - 15);

                // Botón eliminar
                if (this.id !== "start") {
                    ctx.fillStyle = "#f44336";
                    ctx.fillRect(this.x + this.width - 25, this.y + 5, 20, 20);
                    ctx.fillStyle = "#fff";
                    ctx.font = "bold 14px sans-serif";
                    ctx.fillText("✕", this.x + this.width - 21, this.y + 20);
                }
                return;
            }

            if (this.type === "audio") {
                ctx.fillStyle = "#fff";
                ctx.strokeStyle = "#444";
                ctx.fillRect(this.x, this.y, this.width, this.height);
                ctx.strokeRect(this.x, this.y, this.width, this.height);

                ctx.fillStyle = "#222";
                ctx.font = "13px sans-serif";
                ctx.fillText(this.text, this.x + 10, this.y + 25);
                ctx.fillText(`Tipo: audio`, this.x + 10, this.y + 45);

                const audioName = this.config.url ? this.config.url.split('/').pop() : "Sin archivo";
                ctx.fillText(`Archivo: ${audioName}`, this.x + 10, this.y + 65);

                // Entrada
                const inPos = this.getInputConnector();
                ctx.beginPath();
                ctx.arc(inPos.x, inPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedIn ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Salida
                const outPos = this.getOutputConnector();
                ctx.beginPath();
                ctx.arc(outPos.x, outPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedOut ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Botón editar
                ctx.fillStyle = "#00bcd4";
                ctx.fillRect(this.x + this.width - 70, this.y + this.height - 30, 60, 20);
                ctx.fillStyle = "#fff";
                ctx.font = "12px sans-serif";
                ctx.fillText("Editar", this.x + this.width - 62, this.y + this.height - 15);

                // Eliminar
                if (this.id !== "start") {
                    ctx.fillStyle = "#f44336";
                    ctx.fillRect(this.x + this.width - 25, this.y + 5, 20, 20);
                    ctx.fillStyle = "#fff";
                    ctx.font = "bold 14px sans-serif";
                    ctx.fillText("✕", this.x + this.width - 21, this.y + 20);
                }

                return;
            }

            if (this.type === "buttons" && this.config.buttons) {
                const outputs = this.config.buttons.slice(0, 3);
                outputs.forEach((btn, i) => {
                    const cx = this.x + this.width - 10;
                    const cy = this.y + 40 + i * 20;
                    ctx.beginPath();
                    ctx.arc(cx, cy, 7, 0, Math.PI * 2);
                    ctx.fillStyle = "#ffffff";
                    ctx.strokeStyle = "#00bcd4";
                    ctx.lineWidth = 2;
                    ctx.fill();
                    ctx.stroke();

                    // Opcional: dibujar índice
                    ctx.fillStyle = "#00bcd4";
                    ctx.font = "10px sans-serif";
                    ctx.fillText(`${i + 1}`, cx - 3, cy + 3);
                });
            }

            if (this.type === "contact") {
                ctx.fillStyle = "#fff";
                ctx.strokeStyle = "#444";
                ctx.fillRect(this.x, this.y, this.width, this.height);
                ctx.strokeRect(this.x, this.y, this.width, this.height);

                ctx.fillStyle = "#222";
                ctx.font = "13px sans-serif";
                ctx.fillText(this.text, this.x + 10, this.y + 25);
                ctx.fillText(`Tipo: contacto`, this.x + 10, this.y + 45);
                ctx.fillText(`Nombre: ${this.config.name || "Sin nombre"}`, this.x + 10, this.y + 65);

                // Entrada
                const inPos = this.getInputConnector();
                ctx.beginPath();
                ctx.arc(inPos.x, inPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedIn ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Salida
                const outPos = this.getOutputConnector();
                ctx.beginPath();
                ctx.arc(outPos.x, outPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedOut ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Botón editar
                ctx.fillStyle = "#00bcd4";
                ctx.fillRect(this.x + this.width - 70, this.y + this.height - 30, 60, 20);
                ctx.fillStyle = "#fff";
                ctx.font = "12px sans-serif";
                ctx.fillText("Editar", this.x + this.width - 62, this.y + this.height - 15);

                // Botón eliminar
                if (this.id !== "start") {
                    ctx.fillStyle = "#f44336";
                    ctx.fillRect(this.x + this.width - 25, this.y + 5, 20, 20);
                    ctx.fillStyle = "#fff";
                    ctx.font = "bold 14px sans-serif";
                    ctx.fillText("✕", this.x + this.width - 21, this.y + 20);
                }

                return;
            }
            if (this.type === "location") {
                ctx.fillStyle = "#fff";
                ctx.strokeStyle = "#444";
                ctx.fillRect(this.x, this.y, this.width, this.height);
                ctx.strokeRect(this.x, this.y, this.width, this.height);

                ctx.fillStyle = "#222";
                ctx.font = "13px sans-serif";
                ctx.fillText(this.text, this.x + 10, this.y + 25);
                ctx.fillText(`Ubicación: ${this.config.name || "Sin nombre"}`, this.x + 10, this.y + 45);
                ctx.fillText(`📍 ${this.config.latitude?.toFixed(3)}, ${this.config.longitude?.toFixed(3)}`, this.x + 10, this.y + 65);

                const inPos = this.getInputConnector();
                ctx.beginPath();
                ctx.arc(inPos.x, inPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedIn ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                const outPos = this.getOutputConnector();
                ctx.beginPath();
                ctx.arc(outPos.x, outPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedOut ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                ctx.fillStyle = "#00bcd4";
                ctx.fillRect(this.x + this.width - 70, this.y + this.height - 30, 60, 20);
                ctx.fillStyle = "#fff";
                ctx.font = "12px sans-serif";
                ctx.fillText("Editar", this.x + this.width - 62, this.y + this.height - 15);

                if (this.id !== "start") {
                    ctx.fillStyle = "#f44336";
                    ctx.fillRect(this.x + this.width - 25, this.y + 5, 20, 20);
                    ctx.fillStyle = "#fff";
                    ctx.font = "bold 14px sans-serif";
                    ctx.fillText("✕", this.x + this.width - 21, this.y + 20);
                }

                return;
            }

            // Nodo START estilo especial
            if (this.id === "start") {
                ctx.fillStyle = "#4CAF50"; // verde
                ctx.strokeStyle = "#388E3C";
                ctx.fillRect(this.x, this.y, this.width, this.height);
                ctx.strokeRect(this.x, this.y, this.width, this.height);
                ctx.fillStyle = "#fff";
                ctx.font = "bold 16px sans-serif";
                ctx.fillText("START", this.x + 10, this.y + 40);

                // Solo salida (derecha)
                const outPos = this.getOutputConnector();
                ctx.beginPath();
                ctx.arc(outPos.x, outPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();
                return;
            }

            // Dibujo base
            ctx.fillStyle = "#fff";
            ctx.strokeStyle = "#444";
            ctx.fillRect(this.x, this.y, this.width, this.height);
            ctx.strokeRect(this.x, this.y, this.width, this.height);

            // Texto
            ctx.fillStyle = "#222";
            ctx.font = "14px sans-serif";
            ctx.fillText(this.text, this.x + 10, this.y + 25);
            ctx.fillText(`Tipo: ${this.type}`, this.x + 10, this.y + 50);

            // Botón "Editar"
            ctx.fillStyle = "#00bcd4";
            ctx.fillRect(this.x + this.width - 70, this.y + this.height - 30, 60, 20);
            ctx.fillStyle = "#fff";
            ctx.font = "12px sans-serif";
            ctx.fillText("Editar", this.x + this.width - 62, this.y + this.height - 15);

            // Conector izquierdo (entrada)
            const inPos = this.getInputConnector();
            ctx.beginPath();
            ctx.arc(inPos.x, inPos.y, 7, 0, Math.PI * 2);
            ctx.fillStyle = isConnectedIn ? "#00bcd4" : "#ffffff";
            ctx.strokeStyle = "#00bcd4";
            ctx.lineWidth = 2;
            ctx.fill();
            ctx.stroke();

            // Eliminar
            if (this.id !== "start") {
                // Botón eliminar (arriba a la derecha)
                ctx.fillStyle = "#f44336";
                ctx.fillRect(this.x + this.width - 25, this.y + 5, 20, 20);
                ctx.fillStyle = "#fff";
                ctx.font = "bold 14px sans-serif";
                ctx.fillText("✕", this.x + this.width - 21, this.y + 20);
            }

            // Conector derecho (salida)
            const outPos = this.getOutputConnector();
            ctx.beginPath();
            ctx.arc(outPos.x, outPos.y, 7, 0, Math.PI * 2);
            ctx.fillStyle = isConnectedOut ? "#00bcd4" : "#ffffff";
            ctx.strokeStyle = "#00bcd4";
            ctx.lineWidth = 2;
            ctx.fill();
            ctx.stroke();

            if (this.type === "document") {
                ctx.fillStyle = "#fff";
                ctx.strokeStyle = "#444";
                ctx.fillRect(this.x, this.y, this.width, this.height);
                ctx.strokeRect(this.x, this.y, this.width, this.height);

                ctx.fillStyle = "#222";
                ctx.font = "13px sans-serif";
                ctx.fillText(this.text, this.x + 10, this.y + 25);
                ctx.fillText(`Tipo: ${this.type}`, this.x + 10, this.y + 45);
                ctx.fillText(`Archivo: ${this.config.filename || "Sin nombre"}`, this.x + 10, this.y + 65);

                // Entrada
                const inPos = this.getInputConnector();
                ctx.beginPath();
                ctx.arc(inPos.x, inPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedIn ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Salida
                const outPos = this.getOutputConnector();
                ctx.beginPath();
                ctx.arc(outPos.x, outPos.y, 7, 0, Math.PI * 2);
                ctx.fillStyle = isConnectedOut ? "#00bcd4" : "#ffffff";
                ctx.strokeStyle = "#00bcd4";
                ctx.lineWidth = 2;
                ctx.fill();
                ctx.stroke();

                // Editar
                ctx.fillStyle = "#00bcd4";
                ctx.fillRect(this.x + this.width - 70, this.y + this.height - 30, 60, 20);
                ctx.fillStyle = "#fff";
                ctx.font = "12px sans-serif";
                ctx.fillText("Editar", this.x + this.width - 62, this.y + this.height - 15);

                // Eliminar
                if (this.id !== "start") {
                    ctx.fillStyle = "#f44336";
                    ctx.fillRect(this.x + this.width - 25, this.y + 5, 20, 20);
                    ctx.fillStyle = "#fff";
                    ctx.font = "bold 14px sans-serif";
                    ctx.fillText("✕", this.x + this.width - 21, this.y + 20);
                }
                return;
            }
        }

        isEditButton(mx, my) {
            return (
                mx >= this.x + this.width - 70 &&
                mx <= this.x + this.width - 10 &&
                my >= this.y + this.height - 30 &&
                my <= this.y + this.height - 10
            );
        }

        isOutputCircle(mx, my) {
            const c = this.getOutputConnector();
            return Math.hypot(mx - c.x, my - c.y) <= 8;
        }

        isInputCircle(mx, my) {
            const c = this.getInputConnector();
            return Math.hypot(mx - c.x, my - c.y) <= 8;
        }

        isDeleteButton(mx, my) {
            return this.id !== "start" &&
                mx >= this.x + this.width - 25 &&
                mx <= this.x + this.width - 5 &&
                my >= this.y + 5 &&
                my <= this.y + 25;
        }
    }

    const nodes = [
        new Node("start", 50, 120, "Incio", "start")
    ];

    let connections = [];
    let connectingFrom = null;
    let draggingNode = null;
    let offsetX = 0;
    let offsetY = 0;
    let isModalOpen = false;
    let mouseX = 0;
    let mouseY = 0;
    let hoveredConnection = null;
    let nodeCounter = 1; // Para ID únicos

    $('.node-button').on('click', function () {
        const type = $(this).data('type');
        const newId = `node${nodeCounter++}`;
        const newNode = new Node(newId, 300 + (nodeCounter * 30), 150 + (nodeCounter * 20), `Nuevo ${type}`, type);
        nodes.push(newNode);
        clearNodeModal();
        render();
    });

    const modalEl = $('#nodeModal')[0];
    const modalInstance = new Modal(modalEl);

    $(modalEl).on('shown.bs.modal', () => { isModalOpen = true; });
    $(modalEl).on('hidden.bs.modal', () => {
        isModalOpen = false;
        draggingNode = null;
    });

    $('#editNodeForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#nodeId').val();
        const node = nodes.find(n => n.id === id);
        if (node) {
            node.text = $('#nodeText').val();
            // 🔧 Solo si el tipo es "buttons", actualizar su config
            if (node.type === "buttons") {
                const buttons = [];
                $('#buttonOptionsContainer .button-label').each(function () {
                    const label = $(this).val();
                    const value = $(this).closest('div').find('.button-value').val();
                    if (label && value) {
                        buttons.push({ label, value });
                    }
                });
                node.config.buttons = buttons;
            }
            if (node.type === "list") {
                const items = [];
                $('#listOptionsContainer .list-label').each(function () {
                    const label = $(this).val();
                    const value = $(this).closest('div').find('.list-value').val();
                    if (label && value) {
                    items.push({ label, value });
                    }
                });
                node.config.items = items;
            }
            if (node.type === "image") {
                const url = $('#imageUrl').val().trim();
                const caption = $('#imageCaption').val().trim();
                // Validación de extensión de imagen
                const validExtensions = /\.(jpg|jpeg|png|gif|webp)$/i;
                if (!validExtensions.test(url)) {
                    msg('Aguarda! Imagen no admitida','La URL debe ser una imagen válida (.jpg, .jpeg, .png, .gif, .webp)','warning');
                    return;
                }
                // Validación de formato de URL
                try {
                    new URL(url); // lanza error si la URL es inválida
                } catch (e) {
                    msg('Aguarda! origen no admitido','La URL no es válida. Asegúrate de que sea accesible públicamente.','warning');
                    return;
                }
                // Si todo está bien, guardar
                node.config.url = url;
                node.config.caption = caption;
            }

            if (node.type === "document") {
                const url = $('#documentUrl').val().trim();
                const filename = $('#documentFilename').val().trim();

                // Validación de extensión
                const validDocExt = /\.(pdf|docx|xlsx|pptx)$/i;
                if (!validDocExt.test(url)) {
                    msg('Aguarda! archivo no admitido','La URL debe terminar en .pdf, .docx, .xlsx o .pptx.','warning');
                    return;
                }

                // Validación de URL válida
                try {
                    new URL(url);
                } catch {
                    alert("La URL no es válida.");
                    return;
                }

                node.config.url = url;
                node.config.filename = filename || "Documento.pdf";
            }

            if (node.type === "audio") {
                const url = $('#audioUrl').val().trim();

                // Validación de extensión de audio
                const validAudioExt = /\.(mp3|ogg|wav|m4a)$/i;
                if (!validAudioExt.test(url)) {
                    msg('Aguarda! archivo de audio no admitido','La URL debe ser un archivo de audio válido (.mp3, .ogg, .wav, .m4a)','warning');
                    return;
                }

                // Validación de URL válida
                try {
                    new URL(url);
                } catch {
                    msg("Error!","La URL no es válida.","error");
                    return;
                }

                node.config.url = url;
            }

            if (node.type === "contact") {
                const name = $('#contactName').val().trim();
                const phone = $('#contactPhone').val().trim();

                // Validar teléfono básico
                const isPhoneValid = /^\+\d{10,15}$/.test(phone);
                if (!isPhoneValid) {
                    msg('Aguarda! número admitido','Número inválido. Usa el formato internacional: +593xxxxxxxxx','warning');
                    return;
                }

                if (!name) {
                    msg('Aguarda! nombre del contacto','Debes ingresar un nombre para el contacto.','warning');
                    return;
                }

                node.config.name = name;
                node.config.phone = phone;

            }
            if (node.type === "location") {
                const name = $('#locationName').val().trim();
                const address = $('#locationAddress').val().trim();
                const latitude = parseFloat($('#locationLat').val());
                const longitude = parseFloat($('#locationLng').val());

                if (!name || !address || isNaN(latitude) || isNaN(longitude)) {
                    msg('Aguarda! Datos no validos','Debes completar  los campos con datos válidos.','warning');
                    return;
                }

                node.config.name = name;
                node.config.address = address;
                node.config.latitude = latitude;
                node.config.longitude = longitude;
            }

            render();
        }
        modalInstance.hide();
    });

        function getMousePos(e) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    function removeConnection(conn) {
        connections = connections.filter(c => c !== conn);
        render();
    }

    $('#builderCanvas').on('mousedown', function (e) {
        if (isModalOpen) return;

        const { x, y } = getMousePos(e);
        mouseX = x;
        mouseY = y;
        draggingNode = null;

        // Eliminar conexión si está activa
        if (hoveredConnection) {
            removeConnection(hoveredConnection);
            hoveredConnection = null;
            return;
        }

        // Detectar botones dentro de nodos
        for (let i = 0; i < nodes.length; i++) {
            const node = nodes[i];

            // Eliminar nodo
            if (node.isDeleteButton(x, y)) {
                connections = connections.filter(c => c.from !== node.id && c.to !== node.id);
                nodes.splice(i, 1);
                clearNodeModal();
                render();
                return;
            }

            // Iniciar conexión
            if (node.isOutputCircle(x, y)) {
                connectingFrom = node;
                return;
            }

            // Finalizar conexión
            if (node.isInputCircle(x, y) && connectingFrom && connectingFrom.id !== node.id) {
                connections.push({ from: connectingFrom.id, to: node.id });
                connectingFrom = null;
                render();
                return;
            }
            if (node.isInputCircle(x, y) && connectingFrom && connectingFrom.node.id !== node.id) {
                connections.push({
                    from: connectingFrom.node.id,
                    to: node.id,
                    via: connectingFrom.index
                });
                connectingFrom = null;
                render();
                return;
            }
            const index = node.getButtonConnectorIndex(x, y);
            if (index !== -1 && index !== null) {
                connectingFrom = { node, index };
                return;
            }

            // Abrir modal editar
            if (node.isEditButton(x, y)) {
                if (node.type === "list") {
                    // 🧼 Ocultamos ambas secciones condicionales del modal
                    $('#buttonsConfigSection').hide();
                    $('#listConfigSection').hide();
                    $('#buttonOptionsContainer').empty();
                    $('#listOptionsContainer').empty();

                    const items = node.config.items || [];
                    items.forEach(item => {
                        $('#listOptionsContainer').append(`
                        <div class="mb-2 d-flex gap-2">
                            <input type="text" class="form-control form-control-sm list-label" value="${item.label}" placeholder="Texto del ítem">
                            <input type="text" class="form-control form-control-sm list-value" value="${item.value}" placeholder="Valor interno">
                        </div>
                        `);
                    });
                }

                if (node.type === "buttons") {
                    $('#buttonsConfigSection').show();
                    $('#buttonOptionsContainer').empty();
                    const existing = node.config.buttons || [];
                    existing.forEach((btn, i) => {
                    $('#buttonOptionsContainer').append(`
                        <div class="mb-2 d-flex gap-2">
                            <input type="text" class="form-control form-control-sm button-label" value="${btn.label}" placeholder="Texto del botón">
                            <input type="text" class="form-control form-control-sm button-value" value="${btn.value}" placeholder="Valor interno">
                        </div>`);
                    });
                }
                // Configuración dinámica si el nodo es tipo list
                if (node.type === "list") {
                    $('#listConfigSection').show();
                    const items = node.config.items || [];
                    items.forEach(item => {
                    $('#listOptionsContainer').append(`
                        <div class="mb-2 d-flex gap-2">
                        <input type="text" class="form-control form-control-sm list-label" value="${item.label}" placeholder="Texto del ítem">
                        <input type="text" class="form-control form-control-sm list-value" value="${item.value}" placeholder="Valor interno">
                        </div>`);
                    });
                }
                $('#imageConfigSection').hide();

                if (node.type === "image") {
                    $('#imageConfigSection').show();
                    $('#imageUrl').val(node.config.url || "");
                    $('#imageCaption').val(node.config.caption || "");
                }

                $('#nodeId').val(node.id);
                $('#nodeText').val(node.text);
                $('#nodeModalLabel').text(`Editar Nodo: ${node.text}`);
                modalInstance.show();

                $('#documentConfigSection').hide();

                if (node.type === "document") {
                    $('#documentConfigSection').show();
                    $('#documentUrl').val(node.config.url || "");
                    $('#documentFilename').val(node.config.filename || "");
                }
                $('#audioConfigSection').hide();

                if (node.type === "audio") {
                    $('#audioConfigSection').show();
                    $('#audioUrl').val(node.config.url || "");
                }

                $('#contactConfigSection').hide();

                if (node.type === "contact") {
                    $('#contactConfigSection').show();
                    $('#contactName').val(node.config.name || "");
                    $('#contactPhone').val(node.config.phone || "");
                }

                $('#locationConfigSection').hide();

                if (node.type === "location") {
                    $('#locationConfigSection').show();
                    $('#locationName').val(node.config.name || "");
                    $('#locationAddress').val(node.config.address || "");
                    $('#locationLat').val(node.config.latitude || "");
                    $('#locationLng').val(node.config.longitude || "");
                }
                return;
            }

            // Iniciar drag
            if (
                x >= node.x &&
                x <= node.x + node.width &&
                y >= node.y &&
                y <= node.y + node.height
            ) {
                draggingNode = node;
                offsetX = x - node.x;
                offsetY = y - node.y;
                return;
            }
        }

    });

    $('#builderCanvas').on('mousemove', function (e) {
        const { x, y } = getMousePos(e);
        mouseX = x;
        mouseY = y;

        // Hover sobre conexión
        hoveredConnection = null;
        for (let conn of connections) {
            const fromNode = nodes.find(n => n.id === conn.from);
            const toNode = nodes.find(n => n.id === conn.to);
            if (!fromNode || !toNode) continue;

            const from = fromNode.getOutputConnector();
            const to = toNode.getInputConnector();
            const midX = (from.x + to.x) / 2;
            const midY = (from.y + to.y) / 2;

            if (Math.hypot(mouseX - midX, mouseY - midY) < 15) {
                hoveredConnection = conn;
                break;
            }
        }

        // Drag activo
        if (draggingNode && !isModalOpen) {
            draggingNode.x = x - offsetX;
            draggingNode.y = y - offsetY;
        }

        render();
    });

    $('#builderCanvas').on('mouseup', function () {
        draggingNode = null;
    });

    $('#addButtonOption').on('click', function () {
        const count = $('#buttonOptionsContainer .button-label').length;
        if (count >= 3) {
            alert("WhatsApp solo permite 3 botones.");
            return;
        }
        $('#buttonOptionsContainer').append(`
            <div class="mb-2 d-flex gap-2">
            <input type="text" class="form-control form-control-sm button-label" placeholder="Texto del botón">
            <input type="text" class="form-control form-control-sm button-value" placeholder="Valor interno">
            </div>`
        );
    });

    $('#addListItem').on('click', function () {
        const count = $('#listOptionsContainer .list-label').length;
        if (count >= 10) {
            alert("WhatsApp permite hasta 10 ítems por lista.");
            return;
        }
        $('#listOptionsContainer').append(`
            <div class="mb-2 d-flex gap-2">
            <input type="text" class="form-control form-control-sm list-label" placeholder="Texto del ítem">
            <input type="text" class="form-control form-control-sm list-value" placeholder="Valor interno">
            </div>
        `);
    });

    $('#exportFlowBtn').on('click', function () {
        const jsonText = getFlowAsJSON();
        console.log(jsonText); // o copiar al portapapeles, guardar, etc.
    });

    function drawConnections() {
        connections.forEach(conn => {
            const fromNode = nodes.find(n => n.id === conn.from);
            const toNode = nodes.find(n => n.id === conn.to);
            if (!fromNode || !toNode) return;

            const from = fromNode.getOutputConnector();
            const to = toNode.getInputConnector();

            ctx.beginPath();
            ctx.moveTo(from.x, from.y);
            ctx.lineTo(to.x, to.y);
            ctx.strokeStyle = "#00bcd4";
            ctx.lineWidth = 2;
            ctx.stroke();

            // Punto medio: mostrar etiqueta "Quitar vínculo"
            const midX = (from.x + to.x) / 2;
            const midY = (from.y + to.y) / 2;

            if (hoveredConnection === conn) {
                ctx.fillStyle = "#f44336";
                ctx.font = "12px sans-serif";
                ctx.fillText("Quitar vínculo", midX - 30, midY - 10);
            }
        });

        // Línea flotante para conexión activa
        if (connectingFrom) {
            const start = connectingFrom.getOutputConnector();
            ctx.beginPath();
            ctx.moveTo(start.x, start.y);
            ctx.lineTo(mouseX, mouseY);
            ctx.strokeStyle = "#ff9800";
            ctx.lineWidth = 2;
            ctx.setLineDash([5, 5]);
            ctx.stroke();
            ctx.setLineDash([]);
        }
    }

    function clearNodeModal() {
        $('#nodeId').val('');
        $('#nodeText').val('');
        $('#buttonsConfigSection').hide();
        $('#buttonOptionsContainer').empty();
    }


    function render() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        // Dibujo de fondo punteado
        const gridCanvas = document.createElement('canvas');
        gridCanvas.width = 20;
        gridCanvas.height = 20;
        const gridCtx = gridCanvas.getContext('2d');

        gridCtx.fillStyle = "#e0e0e0"; // gris claro
        gridCtx.beginPath();
        gridCtx.arc(10, 10, 1.5, 0, Math.PI * 2); // punto en el centro
        gridCtx.fill();

        const pattern = ctx.createPattern(gridCanvas, 'repeat');
        ctx.fillStyle = pattern;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        drawConnections();
        nodes.forEach(node => {
            const isConnectedOut = connections.some(c => c.from === node.id);
            const isConnectedIn = connections.some(c => c.to === node.id);
            node.draw(ctx, isConnectedIn, isConnectedOut);
        });
    }

    function getFlowAsJSON() {
        const flow = {
            nodes: nodes.map(n => ({
            id: n.id,
            type: n.type,
            text: n.text,
            x: n.x,
            y: n.y,
            config: n.config || {}
            })),
            connections: connections.map(c => ({
            from: c.from,
            to: c.to,
            ...(c.via !== undefined ? { via: c.via } : {})
            }))
        };
        return JSON.stringify(flow, null, 2); // 🧼 JSON legible
    }

    function msg(title,text,icon){
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
        });
        return;
    }

    //const jsonText = getFlowAsJSON();
    // enviarlo a Laravel vía POST, guardarlo local, etc.
    function importFlowFromJSON(jsonText) {
        try {
            const flow = JSON.parse(jsonText);

            // 🧼 Resetear estructuras
            nodes = [];
            connections = [];
            nodeCounter = 1;

            // 🚧 Cargar nodos
            flow.nodes.forEach(n => {
            const newNode = new Node(n.id, n.x, n.y, n.text, n.type, n.config || {});
            nodes.push(newNode);
            nodeCounter++;
            });

            // 🔗 Cargar conexiones
            flow.connections.forEach(c => {
            connections.push({
                from: c.from,
                to: c.to,
                ...(c.via !== undefined ? { via: c.via } : {})
            });
            });

            render(); // 🎨 Actualizar canvas
            msg("Enhorabuena!","¡Flujo cargado exitosamente!","success");
        } catch (err) {
            console.error("Error al importar flujo:", err);
            msg("Error!","El JSON no tiene formato válido o está incompleto.","error");
        }
    }

    render();

});
