<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Salas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

    <div class="container">
        <h1 class="mt-5"><i class="fas fa-door-open"></i> Salas</h1>
        <a href="<?php echo site_url('salas/create'); ?>" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Crear Sala
        </a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><i class="fas fa-tag"></i> Nombre</th>
                    <th><i class="fas fa-align-left"></i> Descripción</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salas as $sala): ?>
                <tr>
                    <td><?php echo $sala['nombre']; ?></td>
                    <td><?php echo $sala['descripcion']; ?></td>
                    <td>
                        <a href="<?php echo site_url('salas/edit/'.$sala['id']); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?php echo site_url('salas/delete/'.$sala['id']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta sala?');">
                            <i class="fas fa-trash-alt"></i> Eliminar
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
