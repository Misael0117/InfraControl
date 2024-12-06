<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $type = $_POST['type']; // Obtener el tipo (producto)

    // Si la acción es añadir un nuevo producto
    if ($action == 'add' && $type == 'producto') {
        $clave = $_POST['clave'];
        $producto = $_POST['producto'];
        $categoria = $_POST['categoria'];

        try {
            $query = "INSERT INTO catalago_productos (clave, producto, categoria) 
                      VALUES (:clave, :producto, :categoria)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':producto', $producto);
            $stmt->bindParam(':categoria', $categoria);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Producto añadido exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al añadir el producto.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    // Si la acción es editar un producto existente
    } elseif ($action == 'edit' && $type == 'producto') {
        $id = $_POST['id']; // ID del registro que se va a editar
        $clave = $_POST['clave'];
        $producto = $_POST['producto'];
        $categoria = $_POST['categoria'];

        try {
            $query = "UPDATE catalago_productos SET clave = :clave, producto = :producto, categoria = :categoria 
                      WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':producto', $producto);
            $stmt->bindParam(':categoria', $categoria);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Producto actualizado exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al actualizar el producto.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    // Si la acción es eliminar un producto
    } elseif ($action == 'delete' && $type == 'producto') {
        $id = $_POST['id'];

        try {
            $query = "DELETE FROM catalago_productos WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $response = array('success' => true, 'message' => 'Producto eliminado exitosamente.');
            } else {
                $response = array('success' => false, 'message' => 'Error al eliminar el producto.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
        }

    } else {
        $response = array('success' => false, 'message' => 'Acción no reconocida o tipo de producto no válido.');
    }

    echo json_encode($response);
}
?>
