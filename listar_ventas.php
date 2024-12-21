<?php
// listar_ventas.php
include 'conexion.php';

// Verificar que el usuario esté autenticado (ajusta según tu sistema de autenticación)
$usuario_id = 1; // Supongamos que el usuario está autenticado

// Obtener las ventas del usuario, junto con los datos de la caja
$sql = "SELECT v.id, v.total, v.metodo_pago, v.monto_pagado, v.vuelto, v.fecha, c.fecha_apertura AS fecha_apertura_caja, c.fecha_cierre AS fecha_cierre_caja, c.monto_inicial, c.monto_final
        FROM ventas v
        INNER JOIN cajas c ON v.caja_id = c.id
        WHERE v.usuario_id = ?
        ORDER BY v.fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<!-- index.php -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Ventas</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="container">
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="listar_ventas.php">listar ventas</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

    <h1>Listado de Ventas</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped-columns">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Total</th>
                    <th>Método de Pago</th>
                    <th>Monto Pagado</th>
                    <th>Vuelto</th>
                    <th>Fecha de Venta</th>
                    <th>Fecha Apertura Caja</th>
                    <th>Fecha Cierre Caja</th>
                    <th>Monto Inicial Caja</th>
                    <th>Monto Final Caja</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($venta = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $venta['id']; ?></td>
                        <td><?php echo number_format($venta['total'], 0, ',', '.'); ?> CLP</td>
                        <td><?php echo ucfirst($venta['metodo_pago']); ?></td>
                        <td><?php echo number_format($venta['monto_pagado'], 0, ',', '.'); ?> CLP</td>
                        <td><?php echo number_format($venta['vuelto'], 0, ',', '.'); ?> CLP</td>
                        <td><?php echo $venta['fecha']; ?></td>
                        <td><?php echo $venta['fecha_apertura_caja']; ?></td>
                        <td><?php echo $venta['fecha_cierre_caja']; ?></td>
                        <td><?php echo number_format($venta['monto_inicial'], 0, ',', '.'); ?> CLP</td>
                        <td><?php echo number_format($venta['monto_final'], 0, ',', '.'); ?> CLP</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron ventas para este usuario.</p>
    <?php endif; ?>


        <!-- Bootstrap 5 JS and Popper.js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
