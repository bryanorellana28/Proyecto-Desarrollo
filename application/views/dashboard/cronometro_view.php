<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronómetro Sala</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container">
    <h2 class="text-center mt-4">Cronómetro para la Sala</h2>
    <form id="formCronometro" action="<?php echo site_url('dashboard/finalizar_cronometro'); ?>" method="post">
        <input type="hidden" name="id_sala" value="<?php echo $sala['id']; ?>">
        <div class="form-group">
            <label for="cliente">Cliente:</label>
            <select id="cliente" name="id_cliente" class="form-control" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="paquete">Paquete:</label>
            <select id="paquete" name="id_paquete" class="form-control" required>
                <?php foreach ($paquetes as $paquete): ?>
                    <option value="<?php echo $paquete['id']; ?>"><?php echo $paquete['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tiempo">Tiempo (minutos):</label>
            <input type="number" id="tiempo" name="tiempo" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label>Tiempo Restante:</label>
            <p id="tiempoRestante">00:00</p>
        </div>
        <button type="button" class="btn btn-success" onclick="startTimer(document.getElementById('tiempo').value)">Iniciar</button>
        <button type="button" class="btn btn-warning" onclick="iniciarLibre()">Iniciar en modo libre</button>
        <button type="button" class="btn btn-danger" onclick="stopTimer()">Parar</button>
        <button type="submit" class="btn btn-primary">Guardar Cronómetro</button>
    </form>
</div>

<script>
    let timer;
    let startTime;
    let isFreeMode = false;

    document.addEventListener("DOMContentLoaded", () => {
        if (localStorage.getItem('isRunning')) {
            startTimer(localStorage.getItem('remainingTime'), localStorage.getItem('isFreeMode') === 'true');
        }
    });

    function startTimer(tiempo, freeMode = false) {
        isFreeMode = freeMode;
        const tiempoRestanteElem = document.getElementById('tiempoRestante');
        startTime = Date.now();
        localStorage.setItem('isRunning', 'true');
        localStorage.setItem('startTime', startTime);

        if (!freeMode) {
            const totalMilliseconds = tiempo * 60 * 1000;
            localStorage.setItem('remainingTime', tiempo);
            timer = setInterval(() => {
                const elapsed = Date.now() - startTime;
                const remaining = totalMilliseconds - elapsed;

                if (remaining <= 0) {
                    clearInterval(timer);
                    tiempoRestanteElem.textContent = '00:00';
                    localStorage.removeItem('isRunning');
                    alert('El tiempo ha finalizado');
                } else {
                    const minutes = Math.floor(remaining / 60000);
                    const seconds = Math.floor((remaining % 60000) / 1000);
                    tiempoRestanteElem.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                }
            }, 1000);
        } else {
            timer = setInterval(() => {
                const elapsed = Math.floor((Date.now() - startTime) / 1000);
                const minutes = Math.floor(elapsed / 60);
                const seconds = elapsed % 60;
                tiempoRestanteElem.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }, 1000);
        }
    }

    function stopTimer() {
        clearInterval(timer);
        localStorage.removeItem('isRunning');
        document.getElementById('tiempoRestante').textContent = '00:00';
        alert('El cronómetro ha sido detenido');
    }

    function iniciarLibre() {
        document.getElementById('tiempo').disabled = true;
        startTimer(0, true);
    }
</script>
</body>
</html>
