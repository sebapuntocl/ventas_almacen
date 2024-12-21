<?php
// caja.php
include 'conexion.php';

$usuario_id = 1; // Usuario autenticado, para simplificar el ejemplo

// Revisar la acción de apertura o cierre
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'abrir') {
        $monto_inicial = intval($_POST['monto_inicial']);
        if ($monto_inicial <= 0) {
            die("El monto de apertura debe ser mayor a 0.");
        }
        abrirCaja($usuario_id, $monto_inicial);
    } elseif ($accion === 'cerrar') {
        cerrarCaja($usuario_id);
    }

    // Redirigir al index después de realizar la acción
    header('Location: index.php');
    exit();
}

// Función para abrir caja
function abrirCaja($usuario_id, $monto_inicial) {
    global $conn;

    // Cerrar cualquier caja anterior que esté abierta (si existe)
    $sqlCerrarAnterior = "UPDATE cajas SET estado = 'cerrada', fecha_cierre = NOW(), monto_final = 0 WHERE usuario_id = ? AND estado = 'abierta'";
    $stmtCerrar = $conn->prepare($sqlCerrarAnterior);
    $stmtCerrar->bind_param('i', $usuario_id);
    $stmtCerrar->execute();

    // Abrir una nueva caja
    $sql = "INSERT INTO cajas (usuario_id, monto_inicial, estado) VALUES (?, ?, 'abierta')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $usuario_id, $monto_inicial);
    $stmt->execute();
}

// Función para cerrar caja
function cerrarCaja($usuario_id) {
    global $conn;

    // Calcular el monto final sumando todas las ventas registradas desde la apertura
    $sqlMontoFinal = "SELECT SUM(total) AS monto_final FROM ventas WHERE usuario_id = ? AND fecha >= (SELECT fecha_apertura FROM cajas WHERE usuario_id = ? AND estado = 'abierta' ORDER BY fecha_apertura DESC LIMIT 1)";
    $stmtMontoFinal = $conn->prepare($sqlMontoFinal);
    $stmtMontoFinal->bind_param('ii', $usuario_id, $usuario_id);
    $stmtMontoFinal->execute();
    $result = $stmtMontoFinal->get_result();
    $row = $result->fetch_assoc();
    $monto_final = $row['monto_final'] ?? 0;

    // Cerrar la caja actualizando el monto final y la fecha de cierre
    $sqlCerrarCaja = "UPDATE cajas SET estado = 'cerrada', fecha_cierre = NOW(), monto_final = ? WHERE usuario_id = ? AND estado = 'abierta'";
    $stmtCerrarCaja = $conn->prepare($sqlCerrarCaja);
    $stmtCerrarCaja->bind_param('ii', $monto_final, $usuario_id);
    $stmtCerrarCaja->execute();
}

// Función para verificar el estado de la caja
function verificarEstadoCaja($usuario_id) {
    global $conn;

    $sql = "SELECT estado FROM cajas WHERE usuario_id = ? ORDER BY fecha_apertura DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $caja = $result->fetch_assoc();

    return $caja && $caja['estado'] === 'abierta';
}
?>
