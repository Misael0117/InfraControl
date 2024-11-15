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

    // Si la acción es eliminar una entrada
    } elseif ($action == 'delete' && $type == 'entrada') {
        $id = $_POST['id'];

        try {
            $query = "DELETE FROM entrada_materiales WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Entrada eliminada exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al eliminar la entrada.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    } else {
        $response = array('success' => false, 'message' => 'Acción no reconocida o tipo de movimiento no válido.');
    }

    echo json_encode($response);
}

if ($action == 'add' && $type == 'salida') {
    // Captura de datos desde POST
    $categoria = $_POST['categoria'];
    $producto = $_POST['producto'];
    $folio = $_POST['folio'];
    $supervisor = $_POST['supervisor'];
    $colonia = $_POST['colonia'];
    $calle = $_POST['calle'];
    $usuario = $_POST['usuario'];
    $contrato = $_POST['contrato'];
    $medidor = $_POST['medidor'];
    $cantidad = $_POST['cantidad'];
    $costo = $_POST['costo'];
    $fecha = $_POST['fecha'];  // Usando 'fecha' para consistencia con el formulario

    try {
        // Consulta SQL para añadir una nueva salida
        $query = "INSERT INTO salida_material (categoria, producto, folio, supervisor, colonia, calle, usuario, contrato, medidor, cantidad, costo, fecha) 
                  VALUES (:categoria, :producto, :folio, :supervisor, :colonia, :calle, :usuario, :contrato, :medidor, :cantidad, :costo, :fecha)";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':folio', $folio);
        $stmt->bindParam(':supervisor', $supervisor);
        $stmt->bindParam(':colonia', $colonia);
        $stmt->bindParam(':calle', $calle);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrato', $contrato);
        $stmt->bindParam(':medidor', $medidor);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':costo', $costo); 
        $stmt->bindParam(':fecha', $fecha);  // Usando 'fecha' para consistencia

        // Ejecución de la consulta
        if ($stmt->execute()) {
            $response = array('success' => true, 'message' => 'Salida agregada exitosamente.');
        } else {
            $response = array('success' => false, 'message' => 'Error al agregar la salida.');
        }
    } catch (PDOException $e) {
        // Manejo de errores de la base de datos
        $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
    }

    // Enviar la respuesta en formato JSON
    echo json_encode($response);
}


?>
