<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfraControl | Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php

// Conexión a la base de datos
include('config.php');

try {
    // Consulta para obtener la información del inventario
    $query = "
        SELECT
            e.categoria AS categoria,
            e.producto AS producto,
            e.cantidad AS cantidad,
            e.costo AS costo,
            COALESCE((
                SELECT SUM(s.cantidad) 
                FROM salida_material s 
                WHERE s.producto = e.producto AND s.categoria = e.categoria
            ), 0) AS total_salidas
        FROM entrada_materiales e
        ORDER BY e.categoria, e.producto
    ";

    // Ejecutar la consulta
    $stmt = $conn->query($query);

    // Verificar si hay resultados
    if ($stmt->rowCount() > 0) {
        $inventario = [];
        // Recorrer los resultados y almacenar los datos en un array
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $inventario[] = $row;
        }

        // Calcular total_entradas, total_costo, total_cantidad, stock_actual y costo_promedio en PHP
        $productos = [];

        foreach ($inventario as $item) {
            $categoria = $item['categoria'];
            $producto = $item['producto'];

            if (!isset($productos[$categoria])) {
                $productos[$categoria] = [];
            }

            if (!isset($productos[$categoria][$producto])) {
                $productos[$categoria][$producto] = [
                    'total_entradas' => 0,
                    'total_salidas' => floatval($item['total_salidas']),
                    'total_cantidad' => 0,
                    'total_costo' => 0,
                    'stock_actual' => 0,
                    'costo_inicial' => floatval($item['costo'])
                ];
            }

            $productos[$categoria][$producto]['total_entradas'] += floatval($item['cantidad']);
            $productos[$categoria][$producto]['total_cantidad'] += floatval($item['cantidad']);
            $productos[$categoria][$producto]['total_costo'] += floatval($item['cantidad']) * floatval($item['costo']);
            $productos[$categoria][$producto]['stock_actual'] = $productos[$categoria][$producto]['total_cantidad'] - $productos[$categoria][$producto]['total_salidas'];
        }

        echo '<div class="container mt-3 animate__animated animate__fadeIn">';
        echo '<a href="home.php" class="btn btn-secondary mb-3">Regresar</a>';
        echo '<table class="table table-striped">';
        echo '<thead>
                <tr>
                    <th>Categoría</th>
                    <th>Producto</th>
                    <th>Total Entradas</th>
                    <th>Total Salidas</th>
                    <th>Stock Actual</th>
                    <th>Total Costo</th>
                    <th>Total Cantidad</th>
                    <th>Costo Promedio</th>
                </tr>
              </thead>';
        echo '<tbody>';

        foreach ($productos as $categoria => $productos_categoria) {
            foreach ($productos_categoria as $producto => $datos) {
                $total_costo = $datos['total_costo'];
                $total_cantidad = $datos['total_cantidad'];
                $costo_promedio = $total_cantidad > 0 ? $total_costo / $total_cantidad : $datos['costo_inicial'];

                echo '<tr>';
                echo '<td>' . htmlspecialchars($categoria) . '</td>';
                echo '<td>' . htmlspecialchars($producto) . '</td>';
                echo '<td>' . htmlspecialchars($datos['total_entradas']) . '</td>';
                echo '<td>' . htmlspecialchars($datos['total_salidas']) . '</td>';
                echo '<td>' . htmlspecialchars($datos['stock_actual']) . '</td>';
                echo '<td>$' . number_format($total_costo, 2) . '</td>';
                echo '<td>' . htmlspecialchars($total_cantidad) . '</td>';
                echo '<td>$' . number_format($costo_promedio, 2) . '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<script>Swal.fire("Sin datos", "No hay datos para mostrar en el inventario.", "info");</script>';
    }
} catch (PDOException $e) {
    echo '<script>Swal.fire("Error", "Error al ejecutar la consulta: ' . $e->getMessage() . '", "error");</script>';
}

?>

</body>
</html>
