<?php
// procesar_venta.php
include 'conexion.php';

$usuario_id = 1; // Supongamos que el usuario ya está autenticado

// Verificar si la caja está abierta
$sqlCaja = "SELECT id, estado FROM cajas WHERE usuario_id = ? ORDER BY fecha_apertura DESC LIMIT 1";
$stmtCaja = $conn->prepare($sqlCaja);
$stmtCaja->bind_param('i', $usuario_id);
$stmtCaja->execute();
$resultCaja = $stmtCaja->get_result();
$caja = $resultCaja->fetch_assoc();

$cajaAbierta = $caja['estado'] === 'abierta';
$caja_id = $caja['id']; // Guardar el id de la caja abierta

if (!$cajaAbierta) {
    die("La caja está cerrada. No se pueden procesar ventas.");
}

$total = floatval($_POST['total']); // Asegúrate de que sea un número decimal

// Método de pago y monto pagado (deben ser enviados desde el formulario)
$metodo_pago = $_POST['metodo_pago']; // 'efectivo' o 'tarjeta'
$monto_pagado = floatval(str_replace('.', '', $_POST['monto_pagado'])); // Asegúrate de que sea un número decimal

// Debugging
// echo "Total: $total<br>"; 
// echo "Método de pago: $metodo_pago<br>"; 
// echo "Monto pagado: $monto_pagado<br>"; 

// Asegúrate de decodificar el JSON de productos
if (empty($_POST['productos'])) {
    die("No se han proporcionado productos.");
}

$productos = json_decode($_POST['productos'], true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error al decodificar los productos: " . json_last_error_msg());
}

// Calcular el vuelto si el método de pago es efectivo
$vuelto = null;
if ($metodo_pago === 'efectivo') {
    $vuelto = $monto_pagado - $total;
} else {
    // Si el método de pago es tarjeta, el vuelto es 0
    $vuelto = 0;
}

// Debugging del vuelto
// echo "Vuelto: $vuelto<br>";

// Insertar la venta con el caja_id
$sql = "INSERT INTO ventas (usuario_id, total, metodo_pago, monto_pagado, vuelto, caja_id) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('idssdi', $usuario_id, $total, $metodo_pago, $monto_pagado, $vuelto, $caja_id);
$stmt->execute();
$venta_id = $stmt->insert_id; // Obtener el ID de la venta recién insertada

// Insertar los detalles de la venta y actualizar stock
foreach ($productos as $producto) {
    // Calcular el subtotal correctamente
    $subtotal = $producto['cantidad'] * $producto['precio'];

    // Insertar los detalles de la venta
    $sql = "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, subtotal)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiidd', $venta_id, $producto['id'], $producto['cantidad'], 
                                $producto['precio'], $subtotal);
    $stmt->execute();

    // Actualizar el stock del producto
    $sql = "UPDATE productos SET stock = stock - ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $producto['cantidad'], $producto['id']);
    $stmt->execute();
}

// Redirigir o mostrar mensaje de éxito
header('Location: index.php');
exit(); // Asegúrate de llamar a exit después de redirigir
?>
