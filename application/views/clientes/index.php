<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1><i class="fas fa-users"></i> Clientes</h1>
        <a href="<?php echo site_url('clientes/create'); ?>" class="btn btn-primary mb-3">
            <i class="fas fa-user-plus"></i> Agregar Cliente
        </a>
        <table class="table table-bordered">
            <thead>
                <tr>
          
                    <th><i class="fas fa-user"></i> Nombre</th>
                    <th><i class="fas fa-envelope"></i> Correo</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
          
                    <td><?php echo $cliente['nombre']; ?></td>
                    <td><?php echo $cliente['correo']; ?></td>
                    <td>
                        <a href="<?php echo site_url('clientes/edit/'.$cliente['id']); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?php echo site_url('clientes/delete/'.$cliente['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">
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
