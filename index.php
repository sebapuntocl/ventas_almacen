<?php
// index.php
include 'conexion.php';
include 'caja.php';

$usuario_id = 1; // Usuario autenticado para este ejemplo

// Verificar si la caja está abierta o cerrada
$cajaAbierta = verificarEstadoCaja($usuario_id);
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


    <h1 class="mb-4">Registrar Venta</h1>


    <?php if (!$cajaAbierta): ?>
        <!-- Mostrar formulario para abrir caja si está cerrada -->
        <div class="alert alert-warning">La caja está cerrada. Debe abrirla para registrar ventas.</div>

        <form action="caja.php" method="POST">
            <div class="form-group">
                <label for="monto_inicial">Monto de Apertura:</label>
                <input type="number" class="form-control" id="monto_inicial" name="monto_inicial" required>
            </div>
            <input type="hidden" name="accion" value="abrir">
            <button type="submit" class="btn btn-success">Abrir Caja</button>
        </form>

    <?php else: ?>

        <form id="ventaForm" action="procesar_venta.php" method="POST">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="codigo_barras" class="form-label">Escanear Código de Barras:</label>
                        <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" placeholder="Escanee el código" autofocus>
                    </div>

                    <button type="button" id="agregarProducto" class="btn btn-primary mb-3">Agregar Producto</button>

                    <h2>Productos en la Venta</h2>

                    <table id="listaProductos" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyProductos">
                            <!-- Aquí se agregarán los productos dinámicamente con JS -->
                        </tbody>
                    </table>

                    <input type="hidden" name="productos" id="productos">
                    <input type="hidden" name="total" id="total">

                    <h3>Total: $<span id="totalDisplay">0</span></h3>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="monto_pagado" class="form-label">Monto Pagado:</label>
                        <input type="text" class="form-control" id="monto_pagado" name="monto_pagado" placeholder="Ingrese el monto pagado" min="0">
                    </div>

                    <!-- Método de pago -->
                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago:</label>
                        <select name="metodo_pago" class="form-select" id="metodo_pago" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pagoExacto" class="form-label">Pago Exacto:</label>
                        <input type="checkbox" id="pagoExacto" disabled>
                    </div>

                    <h3>Vuelto: $<span id="vueltoDisplay">0</span></h3>
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3" id="finalizarVenta" disabled>Finalizar Venta</button>
        </form>


        <form action="caja.php" method="POST" id="formCerrarCaja">
            <input type="hidden" name="accion" value="cerrar">
            <button type="button" class="btn btn-danger" onclick="confirmarCierreCaja()">Cerrar Caja</button>
        </form>
    <?php endif; ?>
    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        let productos = [];
        let total = 0;

        // Función para agregar producto desde el código de barras
        function agregarProductoDesdeCodigo(codigo_barras) {
            fetch(`buscar_producto.php?codigo_barras=${codigo_barras}`)
                .then(response => response.json())
                .then(producto => {
                    if (producto) {
                        agregarProducto(producto);
                    } else {
                        alert('Producto no encontrado');
                    }
                })
                .catch(error => {
                    console.error('Error al buscar el producto:', error);
                    alert('Hubo un error al intentar buscar el producto');
                });
        }


        // Evento al presionar el botón Agregar Producto
        document.getElementById('agregarProducto').addEventListener('click', function() {
            const codigo_barras = document.getElementById('codigo_barras').value;

            if (codigo_barras) {
                agregarProductoDesdeCodigo(codigo_barras);
            }
        });

        // Evento al ingresar en el campo de código de barras
        document.getElementById('codigo_barras').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                const codigo_barras = this.value;
                if (codigo_barras) {
                    agregarProductoDesdeCodigo(codigo_barras);
                    this.value = '';
                }
                event.preventDefault(); // Evitar el comportamiento predeterminado del Enter
            }
        });

        function agregarProducto(producto) {
            const precio = Math.round(Number(producto.precio)) || 0;
            const stock = Math.round(Number(producto.stock)) || 0;

            if (stock === 0) {
                alert('Sin stock');
                return;
            }

            const productoExistente = productos.find(p => p.id === producto.id);

            if (productoExistente) {
                if (productoExistente.cantidad < stock) {
                    productoExistente.cantidad += 1;
                } else {
                    alert('No se puede agregar más, stock insuficiente');
                }
            } else {
                productos.push({
                    id: producto.id,
                    nombre: producto.nombre,
                    precio: precio,
                    cantidad: 1,
                    subtotal: precio
                });
            }

            actualizarTabla();
            document.getElementById('codigo_barras').value = '';
        }

        function actualizarTabla() {
            const tbody = document.getElementById('tbodyProductos'); // Obtener el tbody
            tbody.innerHTML = ''; // Limpiar el contenido actual de la tabla
            total = 0; // Reiniciar el total a 0

            productos.forEach(p => {
                const subtotal = p.precio * p.cantidad;
                total += subtotal;

                // Corregir aquí, el template literal debe estar dentro de una asignación o retorno
                const fila = `
                        <tr>
                            <td>${p.nombre}</td>
                            <td>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${p.id}, -1)">-</button>
                                    <input type="number" class="form-control d-inline" style="width: 60px;" value="${p.cantidad}" min="1" onchange="actualizarCantidad(${p.id}, this.value)">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${p.id}, 1)">+</button>
                                </div>
                            </td>
                            <td>${p.precio.toLocaleString('es-CL')}</td>
                            <td>${subtotal.toLocaleString('es-CL')}</td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="quitarProducto(${p.id})">Quitar</button></td>
                        </tr>
                    `;
                tbody.innerHTML += fila; // Añadir la fila a la tabla
            });

            document.getElementById('totalDisplay').textContent = total.toLocaleString('es-CL');
            document.getElementById('total').value = total;
            document.getElementById('productos').value = JSON.stringify(productos);

            if (productos.length === 0) {
                document.getElementById('monto_pagado').value = '';
                document.getElementById('vueltoDisplay').textContent = '0';
                document.getElementById('finalizarVenta').disabled = true; // Deshabilitar botón si no hay productos
            } else {
                verificarHabilitacionBoton();
            }

            calcularVuelto();
        }


        // Modificar función cambiarCantidad para consultar el stock
        function cambiarCantidad(id, incremento) {
            const producto = productos.find(p => p.id === id);
            if (producto) {
                // Consultamos el stock disponible del producto en la base de datos
                obtenerStock(id).then(stockDisponible => {
                    if (stockDisponible !== null) {
                        const nuevaCantidad = producto.cantidad + incremento;

                        // Verificar si la nueva cantidad no supera el stock disponible
                        if (nuevaCantidad > stockDisponible) {
                            alert(`No puedes agregar más de ${stockDisponible} unidades de ${producto.nombre}`);
                            // Reiniciar la cantidad en el input a 1
                            producto.cantidad = 1; // Resetear la cantidad en el objeto producto
                            actualizarTabla(); // Actualizamos la tabla para reflejar los cambios
                            return;
                        }

                        // Si la nueva cantidad es válida, actualizar la cantidad
                        producto.cantidad = nuevaCantidad;
                        producto.subtotal = producto.precio * producto.cantidad;
                        actualizarTabla();
                    }
                });
            }
        }


        // Modificar función actualizarCantidad para consultar el stock antes de actualizar
        function actualizarCantidad(id, nuevaCantidad) {
            const producto = productos.find(p => p.id === id);
            if (producto) {
                // Consultamos el stock disponible del producto en la base de datos
                obtenerStock(id).then(stockDisponible => {
                    if (stockDisponible !== null) {
                        const cantidad = parseInt(nuevaCantidad, 10);

                        // Verificar si la nueva cantidad no supera el stock disponible
                        if (cantidad > stockDisponible) {
                            alert(`No puedes ingresar más de ${stockDisponible} unidades de ${producto.nombre}`);
                            // Reiniciar la cantidad en el input a 1
                            producto.cantidad = 1; // Resetear la cantidad en el objeto producto
                            actualizarTabla(); // Actualizamos la tabla para reflejar los cambios
                            return;
                        }

                        // Si la cantidad es válida, actualizar la cantidad del producto
                        producto.cantidad = cantidad;
                        producto.subtotal = producto.precio * producto.cantidad;
                        actualizarTabla();
                    }
                });
            }
        }

        function quitarProducto(id) {
            productos = productos.filter(p => p.id !== id);
            actualizarTabla();
        }

        function calcularVuelto() {
            const metodoPagoSeleccionado = document.getElementById('metodo_pago').value;
            const montoPagado = parseFloat(document.getElementById('monto_pagado').value.replace(/\./g, '')) || 0; // Convertir a número sin puntos
            let vuelto = 0;

            if (metodoPagoSeleccionado === 'efectivo') {
                vuelto = montoPagado - total; // Calcular vuelto solo si el método es efectivo
            }

            // Mostrar el vuelto en la interfaz
            document.getElementById('vueltoDisplay').textContent = (metodoPagoSeleccionado === 'tarjeta') ? '0' : vuelto.toLocaleString('es-CL');
        }



        function verificarHabilitacionBoton() {
            const montoPagado = parseFloat(document.getElementById('monto_pagado').value.replace(/\./g, '')) || 0;
            const metodoPagoSeleccionado = document.getElementById('metodo_pago').value;

            // Habilitar el botón solo si hay productos y el monto pagado es suficiente o es tarjeta
            if (productos.length > 0 && (montoPagado >= total || metodoPagoSeleccionado === 'tarjeta')) {
                document.getElementById('finalizarVenta').disabled = false;
            } else {
                document.getElementById('finalizarVenta').disabled = true;
            }
        }


        // Función para consultar el stock disponible de un producto desde la base de datos
        function obtenerStock(idProducto) {
            console.log(idProducto); // Agrega un log para verificar el valor de idProducto
            return fetch('buscar_producto.php?id_producto=' + idProducto)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return null;
                    }
                    return data.stock || 0;
                })
                .catch(error => {
                    console.error('Error al consultar el stock:', error);
                    alert('Hubo un error al consultar el stock');
                    return null;
                });
        }

        function confirmarCierreCaja() {
        // Mostrar una alerta de confirmación antes de proceder
        if (confirm("¿Estás seguro de que deseas cerrar la caja? Esta acción no se puede deshacer.")) {
            // Si el usuario confirma, se envía el formulario
            document.getElementById('formCerrarCaja').submit(); // Enviar el formulario
        } else {
            // Si el usuario cancela, no hacer nada
            console.log("Cierre de caja cancelado.");
        }
    }



        document.getElementById('monto_pagado').addEventListener('input', calcularVuelto);
        document.getElementById('metodo_pago').addEventListener('change', calcularVuelto);
        document.getElementById('monto_pagado').addEventListener('input', verificarHabilitacionBoton);
        document.getElementById('metodo_pago').addEventListener('change', verificarHabilitacionBoton);
    </script>
</body>

</html>