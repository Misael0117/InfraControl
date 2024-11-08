<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $type = $_POST['type'];

    // Añadir nueva entrada o salida
    if ($action == 'add') {
        $tipo = $_POST['tipo'];
        $producto = $_POST['producto'];
        $producto_id = $_POST['producto_id'];
        $factura = $_POST['factura'];
        $proveedor = $_POST['proveedor'];
        $cantidad = $_POST['cantidad'];
        $costo = $_POST['costo'];
        $fecha = $_POST['fecha'];

        if ($type == 'entrada') {
            // Consulta SQL para añadir una nueva entrada
            $query = "INSERT INTO entrada_materiales (tipo, producto, producto_id, factura, proveedor, cantidad, costo, fecha) VALUES (:tipo, :producto, :producto_id, :factura, :proveedor, :cantidad, :costo, :fecha)";
        } else {
            // Nuevos campos adicionales para salidas
            $fecha_salida = $_POST['fecha_salida'];
            $folio = $_POST['folio'];
            $supervisor = $_POST['supervisor'];
            $colonia = $_POST['colonia'];
            $calle = $_POST['calle'];
            $usuario = $_POST['usuario'];
            $contrato = $_POST['contrato'];
            $medidor = $_POST['medidor'];

            // Consulta SQL para añadir una nueva salida
            $query = "INSERT INTO salida_material (tipo, producto, producto_id, factura, proveedor, cantidad, costo, fecha_salida, folio, supervisor, colonia, calle, usuario, contrato, medidor) VALUES (:tipo, :producto, :producto_id, :factura, :proveedor, :cantidad, :costo, :fecha_salida, :folio, :supervisor, :colonia, :calle, :usuario, :contrato, :medidor)";
        }

        // Preparar y vincular parámetros para ejecutar la consulta
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':producto_id', $producto_id);
        $stmt->bindParam(':factura', $factura);
        $stmt->bindParam(':proveedor', $proveedor);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':costo', $costo);
        $stmt->bindParam(':fecha', $fecha);

        // Vincular los parámetros adicionales solo si es una salida
        if ($type == 'salida') {
            $stmt->bindParam(':fecha_salida', $fecha_salida);
            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':supervisor', $supervisor);
            $stmt->bindParam(':colonia', $colonia);
            $stmt->bindParam(':calle', $calle);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':contrato', $contrato);
            $stmt->bindParam(':medidor', $medidor);
        }

        // Ejecutar la consulta y verificar el resultado
        if ($stmt->execute()) {
            $response = array('status' => 'success', 'message' => ucfirst($type) . ' añadida exitosamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'Error al añadir la ' . $type . '.');
        }

    } elseif ($action == 'edit') {
        // Código de actualización (edit) - similar al de 'add' pero con `UPDATE` en lugar de `INSERT`
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
        $producto = $_POST['producto'];
        $producto_id = $_POST['producto_id'];
        $factura = $_POST['factura'];
        $proveedor = $_POST['proveedor'];
        $cantidad = $_POST['cantidad'];
        $costo = $_POST['costo'];
        $fecha = $_POST['fecha'];

        if ($type == 'entrada') {
            // Consulta SQL para actualizar una entrada
            $query = "UPDATE entrada_materiales SET tipo = :tipo, producto = :producto, producto_id = :producto_id, factura = :factura, proveedor = :proveedor, cantidad = :cantidad, costo = :costo, fecha = :fecha WHERE id = :id";
        } else {
            // Consulta SQL para actualizar una salida con nuevos campos
            $fecha_salida = $_POST['fecha_salida'];
            $folio = $_POST['folio'];
            $supervisor = $_POST['supervisor'];
            $colonia = $_POST['colonia'];
            $calle = $_POST['calle'];
            $usuario = $_POST['usuario'];
            $contrato = $_POST['contrato'];
            $medidor = $_POST['medidor'];

            $query = "UPDATE salida_material SET tipo = :tipo, producto = :producto, producto_id = :producto_id, factura = :factura, proveedor = :proveedor, cantidad = :cantidad, costo = :costo, fecha_salida = :fecha_salida, folio = :folio, supervisor = :supervisor, colonia = :colonia, calle = :calle, usuario = :usuario, contrato = :contrato, medidor = :medidor WHERE id = :id";
        }

        // Preparar y vincular parámetros
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':producto_id', $producto_id);
        $stmt->bindParam(':factura', $factura);
        $stmt->bindParam(':proveedor', $proveedor);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':costo', $costo);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':id', $id);

        if ($type == 'salida') {
            $stmt->bindParam(':fecha_salida', $fecha_salida);
            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':supervisor', $supervisor);
            $stmt->bindParam(':colonia', $colonia);
            $stmt->bindParam(':calle', $calle);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':contrato', $contrato);
            $stmt->bindParam(':medidor', $medidor);
        }

        if ($stmt->execute()) {
            $response = array('status' => 'success', 'message' => ucfirst($type) . ' editada exitosamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'Error al editar la ' . $type . '.');
        }

    } elseif ($action == 'delete') {
        // Código para eliminar una entrada o salida
        $id = $_POST['id'];

        if ($type == 'entrada') {
            $query = "DELETE FROM entrada_materiales WHERE id = :id";
        } else {
            $query = "DELETE FROM salida_material WHERE id = :id";
        }

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $response = array('status' => 'success', 'message' => ucfirst($type) . ' eliminada exitosamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'Error al eliminar la ' . $type . '.');
        }
    }

    // Enviar respuesta en formato JSON
    echo json_encode($response);
}
?>
