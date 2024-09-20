<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Sala</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #contenedor { margin: 10px auto; width: 540px; height: 115px; }
        .reloj { float: left; font-size: 80px; font-family: Courier, sans-serif; color: #363431; }
        .disabled { pointer-events: none; opacity: 0.5; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h3 class="text-center display-4 mt-4">Sala: <?= $sala['nombre']; ?></h3>
    <form id="formulario">
        <div class="form-group">
            <label for="cliente">Cliente:</label>
            <select id="cliente" class="form-control">
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id']; ?>"><?= $cliente['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="paquete">Paquete:</label>
            <select id="paquete" class="form-control">
                <?php foreach ($paquetes as $paquete): ?>
                    <option value="<?= $paquete['id']; ?>" data-precio="<?= $paquete['precio_por_hora']; ?>">
                        <?= $paquete['nombre']; ?> ($<?= $paquete['precio_por_hora']; ?>/hora)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="modo">Modo:</label>
            <select id="modo" class="form-control">
                <option value="cronometro">Cronómetro</option>
                <option value="cuenta_regresiva">Cuenta Regresiva</option>
            </select>
        </div>
        <div id="tiempo-group" class="form-group" style="display: none;">
            <label for="tiempo">Tiempo (en minutos):</label>
            <input type="number" id="tiempo" class="form-control" min="1">
        </div>
    </form>
    <div class="container text-center mt-1">
    <div class="container text-center mt-4">
    <div id="contenedor" class="d-flex justify-content-center">
        <div class="reloj display-4" id="Horas">00</div>
        <div class="reloj display-4" id="Minutos">:00</div>
        <div class="reloj display-4" id="Segundos">:00</div>
    </div>
</div>

    
    <div class="mt-4">
        <input type="button" class="btn btn-success mx-2" id="inicio" value="Iniciar" onclick="inicio();">
        <input type="button" class="btn btn-danger mx-2" id="parar" value="Detener" onclick="parar();" disabled>
        <input type="button" class="btn btn-warning mx-2" id="continuar" value="Reanudar" onclick="continuar();" disabled>
        <input type="button" class="btn btn-secondary mx-2" id="reinicio" value="Reiniciar" onclick="reinicio();" disabled>
    </div>
</div>

 
    <div class="text-center mt-4">
    <input type="button" class="btn btn-info" id="liberar" value="Liberar Sala" onclick="liberarSala();">
    <br>
    <a href="<?php echo site_url('dashboard/index/' . $sala['id']); ?>" class="btn btn-secondary mt-2">
        <i class="fas fa-sign-in-alt"></i> regresar
    </a>
</div>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
let control, centesimas = 0, segundos = 0, minutos = 0, horas = 0, tiempoRestante = 0, intervalo = 1000, tiempoInicial, tiempoUltimaActualizacion = Date.now();
document.getElementById("modo").addEventListener("change", function() {
    const modo = this.value;
    document.getElementById("tiempo-group").style.display = (modo === "cuenta_regresiva") ? "block" : "none";
    reinicio();
});
document.addEventListener("DOMContentLoaded", function() {
    const salaId = "<?= $sala['id']; ?>";
    const estadoGuardado = JSON.parse(localStorage.getItem("estado_sala_" + salaId));
    if (estadoGuardado) {
        centesimas = estadoGuardado.centesimas || 0;
        segundos = estadoGuardado.segundos || 0;
        minutos = estadoGuardado.minutos || 0;
        horas = estadoGuardado.horas || 0;
        tiempoRestante = estadoGuardado.tiempoRestante || 0;
        tiempoInicial = estadoGuardado.tiempoInicial || 0;
        if (estadoGuardado.estado === "iniciado") {
            const tiempoTranscurrido = Math.floor((Date.now() - estadoGuardado.ultimaActualizacion) / 1000);
            if (estadoGuardado.modo === "cuenta_regresiva") {
                tiempoRestante = Math.max(0, tiempoRestante - tiempoTranscurrido);
            } else {
                centesimas += (tiempoTranscurrido * 100);
                while (centesimas >= 100) { centesimas -= 100; segundos++; }
                while (segundos >= 60) { segundos -= 60; minutos++; }
            }
        }
        document.getElementById("modo").value = estadoGuardado.modo;
        document.getElementById("tiempo").value = Math.floor(tiempoRestante / 60);
        document.getElementById("Centesimas").innerHTML = ":" + (centesimas < 10 ? "0" : "") + centesimas;
        document.getElementById("Segundos").innerHTML = ":" + (segundos < 10 ? "0" : "") + segundos;
        document.getElementById("Minutos").innerHTML = (minutos < 10 ? "0" : "") + minutos;
        document.getElementById("Horas").innerHTML = (horas < 10 ? "0" : "") + horas;
        if (estadoGuardado.estado === "iniciado") {
            control = setInterval(estadoGuardado.modo === "cuenta_regresiva" ? cronometro : cronometroCronometro, estadoGuardado.modo === "cuenta_regresiva" ? 1000 : 10);
            bloquearFormulario();
        }
    }
});
function guardarEstadoSala(estado) {
    const salaId = "<?= $sala['id']; ?>";
    const estadoActual = {
        estado: estado, centesimas: centesimas, segundos: segundos, minutos: minutos, horas: horas,
        tiempoRestante: tiempoRestante, modo: document.getElementById("modo").value, tiempoInicial: tiempoInicial,
        ultimaActualizacion: Date.now()
    };
    localStorage.setItem("estado_sala_" + salaId, JSON.stringify(estadoActual));
}
function bloquearFormulario() {
    document.getElementById("formulario").classList.add("disabled");
    document.getElementById("inicio").disabled = true;
    document.getElementById("liberar").disabled = false;
    document.getElementById("parar").disabled = false;
    document.getElementById("reinicio").disabled = false;
    document.getElementById("continuar").disabled = false;
}
function desbloquearFormulario() {
    document.getElementById("formulario").classList.remove("disabled");
    document.getElementById("inicio").disabled = false;
    document.getElementById("liberar").disabled = true;
}
function liberarSala() {
    const salaId = "<?= $sala['id']; ?>";
    const paqueteSeleccionado = document.getElementById("paquete").selectedOptions[0];
    const precioPorHora = parseFloat(paqueteSeleccionado.getAttribute("data-precio"));
    const modoSeleccionado = document.getElementById("modo").value;
    let tiempoTotalEnHoras;
    if (modoSeleccionado === "cuenta_regresiva") {
        const tiempoMinutos = parseInt(document.getElementById("tiempo").value);
        tiempoTotalEnHoras = tiempoMinutos / 60;
    } else {
        const horas = parseInt(document.getElementById("Horas").innerHTML) || 0;
        const minutos = parseInt(document.getElementById("Minutos").innerHTML.replace(':', '')) || 0;
        const segundos = parseInt(document.getElementById("Segundos").innerHTML.replace(':', '')) || 0;
        const tiempoTotalEnSegundos = (horas * 3600) + (minutos * 60) + segundos;
        tiempoTotalEnHoras = tiempoTotalEnSegundos / 3600;
    }
    const costoTotal = (tiempoTotalEnHoras * precioPorHora).toFixed(2);
    Swal.fire({ title: "Sala finalizada", text: "El costo total es: $" + costoTotal, icon: "success" });
    parar();
    reinicioliberar();
    localStorage.removeItem("estado_sala_" + salaId);
    document.getElementById("tiempo").value = 0;
    desbloquearFormulario();
}
function inicio() {
    if (document.getElementById("modo").value === "cuenta_regresiva") {
        const tiempoMinutos = parseInt(document.getElementById("tiempo").value);
        if (isNaN(tiempoMinutos) || tiempoMinutos <= 0) {
            Swal.fire("Por favor, ingrese un tiempo válido.");
            return;
        }
        tiempoRestante = tiempoMinutos * 60; // Aquí asegúrate de que este valor sea en segundos
        tiempoInicial = tiempoRestante;
        control = setInterval(cronometro, 1000);
    } else {
        control = setInterval(cronometroCronometro, 10);
    }
    guardarEstadoSala("iniciado");
    bloquearFormulario();
    document.getElementById("continuar").disabled = true;
}

function parar() {
    clearInterval(control);
    guardarEstadoSala("detenido");
    document.getElementById("continuar").disabled = false;
}
function continuar() {
    control = setInterval(document.getElementById("modo").value === "cuenta_regresiva" ? cronometro : cronometroCronometro, document.getElementById("modo").value === "cuenta_regresiva" ? 1000 : 10);
    guardarEstadoSala("iniciado");
    document.getElementById("continuar").disabled = true;
}
function reinicio() {
    clearInterval(control);
    centesimas = 0; segundos = 0; minutos = 0; horas = 0; tiempoRestante = 0;
    document.getElementById("Centesimas").innerHTML = ":00";
    document.getElementById("Segundos").innerHTML = ":00";
    document.getElementById("Minutos").innerHTML = "00";
    document.getElementById("Horas").innerHTML = "00";
    guardarEstadoSala("detenido");
    inicio();
}
function reinicioliberar() {
    clearInterval(control);
    centesimas = 0; segundos = 0; minutos = 0; horas = 0; tiempoRestante = 0;
    document.getElementById("Centesimas").innerHTML = ":00";
    document.getElementById("Segundos").innerHTML = ":00";
    document.getElementById("Minutos").innerHTML = "00";
    document.getElementById("Horas").innerHTML = "00";
    guardarEstadoSala("detenido");
}
function cronometro() {
    if (tiempoRestante > 0) {
        tiempoRestante--;
        let horas = Math.floor(tiempoRestante / 3600);
        let min = Math.floor((tiempoRestante % 3600) / 60);
        let seg = tiempoRestante % 60;

        // Actualiza la visualización en el formato correcto
        document.getElementById("Horas").innerHTML = (horas < 10 ? "0" : "") + horas;
        document.getElementById("Minutos").innerHTML = ":" + (min < 10 ? "0" : "") + min;
        document.getElementById("Segundos").innerHTML = ":" + (seg < 10 ? "0" : "") + seg;
    } else {
        clearInterval(control);
        Swal.fire("¡El tiempo se ha agotado!", "", "success").then(() => {
            liberarSala();
        });
    }
    guardarEstadoSala("iniciado");
}

function cronometroCronometro() {
    // Este método ya no debería tener centésimas, así que solo ajusta el tiempo
    segundos++;
    if (segundos >= 60) { segundos = 0; minutos++; }
    if (minutos >= 60) { minutos = 0; horas++; }

    document.getElementById("Horas").innerHTML = (horas < 10 ? "0" : "") + horas;
    document.getElementById("Minutos").innerHTML = ":" + (minutos < 10 ? "0" : "") + minutos;
    document.getElementById("Segundos").innerHTML = ":" + (segundos < 10 ? "0" : "") + segundos;
    guardarEstadoSala("iniciado");
}

</script>
</body>
</html>
