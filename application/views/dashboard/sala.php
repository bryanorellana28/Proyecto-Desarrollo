<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Sala</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .card {
            margin-top: 20px;
        }
        .alert-info {
            margin-bottom: 20px;
        }
        .btn-icon {
            font-size: 1.2rem;
            padding: 0.5rem 1rem;
        }
        #cronometro-estado {
            font-size: 3rem;
            font-weight: bold;
            color: #333;
            background-color: #f8f9fa;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 500px;
            z-index: 1000;
        }
        .form-group label {
            font-weight: bold;
        }
        #tiempo-group {
            display: none;
        }
        body {
            padding-top: 80px; /* Ajuste para evitar solapamiento con el cronómetro fijo */
        }
    </style>
</head>
<body>

<div id="cronometro-estado">00:00:00</div>

<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Detalles de Sala: <?php echo $sala['nombre']; ?></h3>
        </div>
        <div class="card-body">
            <form id="form-cronometro">
                <input type="hidden" id="sala_id" value="<?php echo $sala['id']; ?>">
                <div class="form-group">
                    <label for="cliente">Cliente:</label>
                    <select id="cliente" name="cliente" class="form-control">
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="paquete">Paquete:</label>
                    <select id="paquete" name="paquete" class="form-control">
                        <?php foreach ($paquetes as $paquete): ?>
                            <option value="<?php echo $paquete['id']; ?>" data-precio="<?php echo $paquete['precio_por_hora']; ?>"><?php echo $paquete['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modo">Modo:</label>
                    <select id="modo" name="modo" class="form-control">
                        <option value="seleccion">Seleccione un modo</option>
                        <option value="cuenta regresiva">Cuenta Regresiva</option>
                        <option value="reloj libre">Reloj Libre</option>
                    </select>
                </div>
                <div class="form-group" id="tiempo-group">
                    <label for="tiempo">Tiempo (en minutos):</label>
                    <input type="number" id="tiempo" name="tiempo" class="form-control" min="1">
                </div>
                <button type="button" id="iniciar" class="btn btn-primary btn-icon">
                    <i class="fas fa-play"></i> Iniciar Cronómetro
                </button>
            </form>

            <hr>

            <h4>Estado del Cronómetro</h4>
            <button type="button" id="detener" class="btn btn-danger btn-icon">
                <i class="fas fa-stop"></i> Detener Cronómetro
            </button>
            
            <hr>

            <a href="<?php echo site_url('dashboard'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
</div>

<!-- Alerta de sonido -->
<audio id="alerta-sonido" src="https://www.soundjay.com/button/beep-07.wav" preload="auto"></audio>

<script>
$(document).ready(function() {
    let interval; // Variable global para el intervalo del cronómetro

    $('#modo').change(function() {
        if ($(this).val() === 'cuenta regresiva') {
            $('#tiempo-group').show();
        } else {
            $('#tiempo-group').hide();
        }
    });

    $('#iniciar').click(function() {
        const salaId = $('#sala_id').val();
        const modo = $('#modo').val();
        let tiempo = $('#tiempo').val();
        const paqueteSeleccionado = $('#paquete option:selected');
        const precioPorHora = parseFloat(paqueteSeleccionado.data('precio'));

        if (modo === 'cuenta regresiva' && tiempo === '') {
            Swal.fire('Error', 'Por favor, ingrese el tiempo.', 'error');
            return;
        }

        const fechaInicio = Date.now();
        const cronometroData = {
            sala_id: salaId,
            modo: modo,
            tiempo_restante: modo === 'cuenta regresiva' ? tiempo * 60 : 0,
            fecha_inicio: fechaInicio,
            estado: 'activo',
            precio_por_hora: precioPorHora
        };

        localStorage.setItem('cronometro_sala_' + salaId, JSON.stringify(cronometroData));

        bloquearFormulario();
        iniciarCronometro(modo, cronometroData.tiempo_restante, fechaInicio, salaId);
    });

    function bloquearFormulario() {
        $('#form-cronometro input, #form-cronometro select, #iniciar').attr('disabled', true);
    }

    function habilitarFormulario() {
        $('#form-cronometro input, #form-cronometro select, #iniciar').attr('disabled', false);
    }

    function iniciarCronometro(modo, tiempoRestante, fechaInicio, salaId) {
        interval = setInterval(function() {
            const tiempoTranscurrido = Math.floor((Date.now() - fechaInicio) / 1000);

            if (modo === 'cuenta regresiva') {
                const tiempoActualizado = tiempoRestante - tiempoTranscurrido;
                if (tiempoActualizado > 0) {
                    $('#cronometro-estado').text(formatTime(tiempoActualizado));
                } else {
                    clearInterval(interval);
                    $('#cronometro-estado').text('00:00:00');
                    mostrarAlertaFinalizacion(salaId);
                }
            } else {
                const tiempoActualizado = tiempoTranscurrido;
                $('#cronometro-estado').text(formatTime(tiempoActualizado));
            }
        }, 1000);
    }

    function formatTime(seconds) {
        const horas = Math.floor(seconds / 3600);
        const minutos = Math.floor((seconds % 3600) / 60);
        const segundos = seconds % 60;
        return [horas, minutos, segundos]
            .map(v => v < 10 ? '0' + v : v)
            .join(':');
    }

    function mostrarAlertaFinalizacion(salaId) {
        const cronometroData = JSON.parse(localStorage.getItem('cronometro_sala_' + salaId));
        const precioPorHora = cronometroData.precio_por_hora;

        let tiempoTotal;
        if (cronometroData.modo === 'cuenta regresiva') {
            // Para cuenta regresiva, calculamos el costo basado en el tiempo ingresado
            tiempoTotal = cronometroData.tiempo_restante;
        } else {
            // Para reloj libre, calculamos el costo basado en el tiempo transcurrido
            tiempoTotal = Math.floor((Date.now() - cronometroData.fecha_inicio) / 1000); // segundos
        }

        const tiempoTotalHoras = (tiempoTotal / 3600).toFixed(2);
        const costoTotal = (precioPorHora * tiempoTotalHoras).toFixed(2);

        Swal.fire({
            icon: 'success',
            title: 'Cronómetro detenido',
            text: `Costo total: $${costoTotal}.`
        });

        document.getElementById('alerta-sonido').play();
    }

    function cargarEstadoCronometro() {
        const salaId = $('#sala_id').val();
        const cronometroData = JSON.parse(localStorage.getItem('cronometro_sala_' + salaId));

        if (cronometroData && cronometroData.estado === 'activo') {
            iniciarCronometro(cronometroData.modo, cronometroData.tiempo_restante, cronometroData.fecha_inicio, salaId);
        }
    }

    $('#detener').click(function() {
        clearInterval(interval);
        const salaId = $('#sala_id').val();
        mostrarAlertaFinalizacion(salaId);
        habilitarFormulario();
        localStorage.removeItem('cronometro_sala_' + salaId);
    });

    cargarEstadoCronometro();
});
</script>

</body>
</html>
