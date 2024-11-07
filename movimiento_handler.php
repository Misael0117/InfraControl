<?php
session_start();
include 'config.php';

$response = ["status" => "error", "message" => "Ocurrió un error."];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        $action = $_POST['action'];
        $stmt = null;

        if ($action === 'add' && isset($_POST['input1'], $_POST['input2'])) {
            $query = "INSERT INTO movimientos (campo1, campo2) VALUES (:input1, :input2)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':input1' => $_POST['input1'],
                ':input2' => $_POST['input2']
            ]);
            $response = ["status" => "success", "message" => "Entrada añadida exitosamente."];

        } elseif ($action === 'edit' && isset($_POST['id'], $_POST['input1'], $_POST['input2'])) {
            $query = "UPDATE movimientos SET campo1 = :input1, campo2 = :input2 WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':input1' => $_POST['input1'],
                ':input2' => $_POST['input2'],
                ':id' => $_POST['id']
            ]);
            $response = ["status" => "success", "message" => "Entrada actualizada exitosamente."];

        } elseif ($action === 'delete' && isset($_POST['id'])) {
            $query = "DELETE FROM movimientos WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->execute([':id' => $_POST['id']]);
            $response = ["status" => "success", "message" => "Entrada eliminada exitosamente."];
        }
    } catch (PDOException $e) {
        $response = ["status" => "error", "message" => "Error de base de datos: " . $e->getMessage()];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
