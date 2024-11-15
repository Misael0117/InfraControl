<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $type = $_POST['type']; // Obtener el tipo (entrada o salida)

    // Si la acción es añadir una nueva salida
    if ($action == 'add' && $type == 'salida') {
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
        $fecha_salida = $_POST['fecha_salida'];

        try {
            $query = "INSERT INTO salida_material (categoria, producto, folio, supervisor, colonia, calle, usuario, contrato, medidor, cantidad, costo, fecha_salida) 
            VALUES (:categoria, :producto, :folio, :supervisor, :colonia, :calle, :usuario, :contrato, :medidor, :cantidad, :costo, :fecha_salida)";
            
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
            $stmt->bindParam(':fecha_salida', $fecha_salida);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Salida agregada exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al agregar la salida.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }
    } elseif ($action == 'delete' && $type == 'salida') {
        $id = $_POST['id'];

        try {
            $query = "DELETE FROM salida_material WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Salida eliminada exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al eliminar la salida.');
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
