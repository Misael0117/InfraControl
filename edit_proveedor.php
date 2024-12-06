<?php
include 'config.php';

$id = $_GET['id'];

try {
    $query = "SELECT * FROM catalago_proveedor WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo '
        <form id="form-edit-proveedor">
            <div class="form-group">
                <label for="proveedor">Proveedor:</label>
                <input type="text" class="form-control" id="proveedor" name="proveedor" value="' . $row['proveedor'] . '" required>
            </div>
        </form>';
    } else {
        echo '<p>No se encontraron datos para este proveedor.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Error al obtener los datos: ' . $e->getMessage() . '</p>';
}
?>
