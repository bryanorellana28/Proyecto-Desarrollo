<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><i class="fas fa-map-marker-alt"></i> Nombre Espacio</th>
                    <th><i class="fas fa-align-left"></i> Descripción</th>
                    <th><i class="fas fa-thermometer-half"></i> Estado</th>
                    <th><i class="fas fa-tools"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salas as $sala): ?>
                <tr>
                    <td><?php echo $sala['nombre']; ?></td>
                    <td><?php echo $sala['descripcion']; ?></td>
                    <td><?php echo $sala['estadoactual']; ?></td>
                    <td>
                        <a href="<?php echo site_url('dashboard/sala/'.$sala['id']); ?>" class="btn btn-info">
                            <i class="fas fa-check-circle"></i> Seleccionar
                        </a>
                       
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
