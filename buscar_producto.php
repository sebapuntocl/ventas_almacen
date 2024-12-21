<?php
// buscar_producto.php
include 'conexion.php'; // Asegúrate de que la conexión esté configurada correctamente

// Verificamos si se pasa el parámetro 'codigo_barras' o 'id_producto'
if (isset($_GET['codigo_barras'])) {
    $codigo_barras = $_GET['codigo_barras'];
    
    // Consulta para obtener el producto por código de barras
    $sql = "SELECT * FROM productos WHERE codigo_barras = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $codigo_barras); // Vinculamos el parámetro
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        // Devolver el producto completo como JSON
        echo json_encode($producto);
    } else {
        echo json_encode(['error' => 'Producto no encontrado']);
    }
} elseif (isset($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];
    
    // Consulta para obtener el stock de un producto por ID
    $sql = "SELECT stock FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_producto); // Vinculamos el parámetro como entero
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        // Devolver solo el stock como JSON
        echo json_encode(['stock' => $producto['stock']]);
    } else {
        echo json_encode(['error' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['error' => 'Faltan parámetros']);
}

$conn->close();
?>
