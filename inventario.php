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

// Función para verificar si la tabla inventario está vacía
function isInventarioEmpty($conn) {
    $query = "SELECT COUNT(*) FROM inventario";
    $stmt = $conn->query($query);
    return $stmt->fetchColumn() == 0;
}

try {
    // Verificar si la tabla inventario está vacía antes de insertar datos
    $shouldInsertData = isInventarioEmpty($conn);

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
            ), 0) AS total_salidas,
            (SELECT e2.costo FROM entrada_materiales e2 WHERE e2.producto = e.producto AND e2.categoria = e.categoria ORDER BY e2.fecha ASC LIMIT 1) AS primer_costo,
            (SELECT e3.costo FROM entrada_materiales e3 WHERE e3.producto = e.producto AND e3.categoria = e.categoria ORDER BY e3.fecha DESC LIMIT 1) AS ultimo_costo
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

        // Calcular total_entradas, total_costo, stock_actual y costo_promedio en PHP
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
                    'total_costo' => 0,
                    'stock_actual' => 0,
                    'costo_inicial' => isset($item['costo']) ? floatval($item['costo']) : 0,
                    'ultimo_costo_promedio' => isset($item['costo']) ? floatval($item['costo']) : 0,
                    'primer_costo' => isset($item['primer_costo']) ? floatval($item['primer_costo']) : 0,
                    'ultimo_costo' => isset($item['ultimo_costo']) ? floatval($item['ultimo_costo']) : 0
                ];
            }

            $productos[$categoria][$producto]['total_entradas'] += isset($item['cantidad']) ? floatval($item['cantidad']) : 0;
            $productos[$categoria][$producto]['total_costo'] += isset($item['cantidad']) && isset($item['costo']) ? floatval($item['cantidad']) * floatval($item['costo']) : 0;
            $productos[$categoria][$producto]['stock_actual'] = $productos[$categoria][$producto]['total_entradas'] - $productos[$categoria][$producto]['total_salidas'];

            // Mantener el costo promedio actualizado
            if ($productos[$categoria][$producto]['stock_actual'] != 0) {
                $productos[$categoria][$producto]['ultimo_costo_promedio'] = $productos[$categoria][$producto]['total_costo'] / $productos[$categoria][$producto]['total_entradas'];
            }
        }

        // Insertar datos solo si la tabla está vacía
        if ($shouldInsertData) {
            $insert_query = "INSERT INTO inventario (categoria, producto, total_entradas, total_salidas, stock, costo_inicial, ultimo_costo, costo_total, costo_promedio) VALUES (:categoria, :producto, :total_entradas, :total_salidas, :stock, :costo_inicial, :ultimo_costo, :costo_total, :costo_promedio)";

            $insert_stmt = $conn->prepare($insert_query);

            foreach ($productos as $categoria => $productos_categoria) {
                foreach ($productos_categoria as $producto => $datos) {
                    $existencias = $productos[$categoria][$producto]['stock_actual'];
                    $total_costo = ($existencias == 0) ? 0 : $existencias * $productos[$categoria][$producto]['ultimo_costo_promedio'];
                    $costo_promedio = $productos[$categoria][$producto]['ultimo_costo_promedio'];

                    $insert_stmt->execute([
                        ':categoria' => $categoria,
                        ':producto' => $producto,
                        ':total_entradas' => $productos[$categoria][$producto]['total_entradas'],
                        ':total_salidas' => $productos[$categoria][$producto]['total_salidas'],
                        ':stock' => $existencias,
                        ':costo_inicial' => $productos[$categoria][$producto]['primer_costo'],
                        ':ultimo_costo' => $productos[$categoria][$producto]['ultimo_costo'],
                        ':costo_total' => $total_costo,
                        ':costo_promedio' => $costo_promedio
                    ]);
                }
            }

            echo '<script>Swal.fire("Éxito", "Datos actulizados con exito.", "success");</script>';
        }

        // Mostrar la tabla de inventario al usuario
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
                    <th>Primer Costo</th>
                    <th>Último Costo</th>
                    <th>Costo Total</th>
                    <th>Costo Promedio</th>
                </tr>
              </thead>';
        echo '<tbody>';

        foreach ($productos as $categoria => $productos_categoria) {
            foreach ($productos_categoria as $producto => $datos) {
                $existencias = $productos[$categoria][$producto]['stock_actual'];
                $total_costo = ($existencias == 0) ? 0 : $existencias * $productos[$categoria][$producto]['ultimo_costo_promedio'];
                $costo_promedio = $productos[$categoria][$producto]['ultimo_costo_promedio'];

                echo '<tr>';
                echo '<td>' . htmlspecialchars($categoria) . '</td>';
                echo '<td>' . htmlspecialchars($producto) . '</td>';
                echo '<td>' . htmlspecialchars($datos['total_entradas']) . '</td>';
                echo '<td>' . htmlspecialchars($datos['total_salidas']) . '</td>';
                echo '<td>' . htmlspecialchars($existencias) . '</td>';
                echo '<td>$' . number_format($datos['primer_costo'], 2) . '</td>';
                echo '<td>$' . number_format($datos['ultimo_costo'], 2) . '</td>';
                echo '<td>$' . number_format($total_costo, 2) . '</td>';
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
