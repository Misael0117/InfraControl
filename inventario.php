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
include('config.php');

try {
    $connect = new PDO("mysql:host=localhost;dbname=infracontrol", "root", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}

try {
    // Consulta para obtener la información del inventario
    $query = "
        SELECT
            e.categoria AS categoria,
            e.producto AS producto,
            SUM(e.cantidad) AS total_entradas,
            SUM(e.costo * e.cantidad) AS total_costo,
            SUM(e.cantidad) AS total_cantidad,
            MAX(e.costo) AS costo_inicial,
            COALESCE((
                SELECT SUM(s.cantidad) 
                FROM salida_material s 
                WHERE s.producto = e.producto AND s.categoria = e.categoria
            ), 0) AS total_salidas,
            SUM(e.cantidad) - COALESCE((
                SELECT SUM(s.cantidad) 
                FROM salida_material s 
                WHERE s.producto = e.producto AND s.categoria = e.categoria
            ), 0) AS stock_actual
        FROM entrada_materiales e
        GROUP BY e.categoria, e.producto
        HAVING stock_actual > 0
        ORDER BY e.categoria, e.producto
    ";

    $stmt = $connect->query($query);

    if ($stmt->rowCount() > 0) {
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

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Verificar datos de costo y cantidad
            $total_costo = floatval($row['total_costo']);
            $total_cantidad = intval($row['total_cantidad']);

            // Calcular el costo promedio
            if ($total_costo > 0 && $total_cantidad > 0) {
                $costo_promedio = $total_costo / $total_cantidad;
            } else {
                $costo_promedio = floatval($row['costo_inicial']);
            }

            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['categoria']) . '</td>';
            echo '<td>' . htmlspecialchars($row['producto']) . '</td>';
            echo '<td>' . htmlspecialchars($row['total_entradas']) . '</td>';
            echo '<td>' . htmlspecialchars($row['total_salidas']) . '</td>';
            echo '<td>' . htmlspecialchars($row['stock_actual']) . '</td>';
            echo '<td>' . htmlspecialchars($total_costo) . '</td>';
            echo '<td>' . htmlspecialchars($total_cantidad) . '</td>';
            echo '<td>$' . number_format($costo_promedio, 2) . '</td>';
            echo '</tr>';
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
