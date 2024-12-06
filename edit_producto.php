<?php
include 'config.php';

$id = $_GET['id'];

try {
    $query = "SELECT * FROM catalago_productos WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo '
        <form id="form-edit-producto">
            <div class="form-group">
                <label for="clave">Clave:</label>
                <input type="text" class="form-control" id="clave" name="clave" value="' . $row['clave'] . '" required>
            </div>
            <div class="form-group">
                <label for="producto">Producto:</label>
                <input type="text" class="form-control" id="producto" name="producto" value="' . $row['producto'] . '" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" class="form-control" id="categoria" name="categoria" value="' . $row['categoria'] . '" required>
            </div>
        </form>';
    } else {
        echo '<p>No se encontraron datos para este producto.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Error al obtener los datos: ' . $e->getMessage() . '</p>';
}
?>
