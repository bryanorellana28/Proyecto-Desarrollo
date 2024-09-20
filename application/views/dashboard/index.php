<div class="container mt-5">
    <h1 class="text-center mb-4">Salas</h1>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salas as $sala): ?>
                <tr>
                    <td><?php echo $sala['id']; ?></td>
                    <td><?php echo $sala['nombre']; ?></td>
                    <td><?php echo $sala['estadoactual']; ?></td>
                    <td>
                        <a href="<?php echo site_url('dashboard/sala/' . $sala['id']); ?>" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Ingreso
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Enlace a Bootstrap y Font Awesome -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
