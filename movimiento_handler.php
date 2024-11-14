<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $type = $_POST['type']; // Obtener el tipo (entrada o salida)

    // Si la acción es añadir una nueva entrada
    if ($action == 'add' && $type == 'entrada') {
        $categoria = $_POST['categoria'];
        $producto = $_POST['producto'];
        $factura = $_POST['factura'];
        $proveedor = $_POST['proveedor'];
        $cantidad = $_POST['cantidad'];
        $costo = $_POST['costo'];
        $fecha = $_POST['fecha'];

        try {
            $query = "INSERT INTO entrada_materiales (categoria, producto, factura, proveedor, cantidad, costo, fecha) VALUES (:categoria, :producto, :factura, :proveedor, :cantidad, :costo, :fecha)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':producto', $producto);
            $stmt->bindParam(':factura', $factura);
            $stmt->bindParam(':proveedor', $proveedor);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':costo', $costo);
            $stmt->bindParam(':fecha', $fecha);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Entrada añadida exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al añadir la entrada.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    // Si la acción es editar una entrada existente
    } elseif ($action == 'edit' && $type == 'entrada') {
        $id = $_POST['id']; // ID del registro que se va a editar
        $categoria = $_POST['categoria'];
        $producto = $_POST['producto'];
        $factura = $_POST['factura'];
        $proveedor = $_POST['proveedor'];
        $cantidad = $_POST['cantidad'];
        $costo = $_POST['costo'];
        $fecha = $_POST['fecha'];

        try {
            $query = "UPDATE entrada_materiales SET categoria = :categoria, producto = :producto, factura = :factura, proveedor = :proveedor, cantidad = :cantidad, costo = :costo, fecha = :fecha WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':producto', $producto);
            $stmt->bindParam(':factura', $factura);
            $stmt->bindParam(':proveedor', $proveedor);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':costo', $costo);
            $stmt->bindParam(':fecha', $fecha);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Entrada actualizada exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al actualizar la entrada.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    } else {
        $response = array('success' => false, 'message' => 'Acción no reconocida o tipo de movimiento no válido.');
    }

    echo json_encode($response);
}
?>
