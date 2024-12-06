<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $type = $_POST['type']; // Obtener el tipo (proveedor)

    // Si la acción es añadir un nuevo proveedor
    if ($action == 'add' && $type == 'proveedor') {
        $proveedor = $_POST['proveedor'];

        try {
            $query = "INSERT INTO catalago_proveedor (proveedor) VALUES (:proveedor)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':proveedor', $proveedor);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Proveedor añadido exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al añadir el proveedor.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    // Si la acción es editar un proveedor existente
    } elseif ($action == 'edit' && $type == 'proveedor') {
        $id = $_POST['id']; // ID del registro que se va a editar
        $proveedor = $_POST['proveedor'];

        try {
            $query = "UPDATE catalago_proveedor SET proveedor = :proveedor WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':proveedor', $proveedor);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Proveedor actualizado exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al actualizar el proveedor.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    // Si la acción es eliminar un proveedor
    } elseif ($action == 'delete' && $type == 'proveedor') {
        $id = $_POST['id'];

        try {
            $query = "DELETE FROM catalago_proveedor WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Proveedor eliminado exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al eliminar el proveedor.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    } else {
        $response = array('success' => false, 'message' => 'Acción no reconocida o tipo de proveedor no válido.');
    }

    echo json_encode($response);
}
?>
