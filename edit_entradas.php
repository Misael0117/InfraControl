<?php
include 'config.php';

$id = $_GET['id'];

try {
    $query = "SELECT * FROM entrada_materiales WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo '
        <form id="form-edit-entrada">
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" class="form-control" id="categoria" name="categoria" value="' . $row['categoria'] . '" required>
            </div>
            <div class="form-group">
                <label for="producto">Producto:</label>
                <input type="text" class="form-control" id="producto" name="producto" value="' . $row['producto'] . '" required>
            </div>
            <div class="form-group">
                <label for="factura">Factura:</label>
                <input type="text" class="form-control" id="factura" name="factura" value="' . $row['factura'] . '" required>
            </div>
            <div class="form-group">
                <label for="proveedor">Proveedor:</label>
                <input type="text" class="form-control" id="proveedor" name="proveedor" value="' . $row['proveedor'] . '" required>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" value="' . $row['cantidad'] . '" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="costo">Costo:</label>
                <input type="number" class="form-control" id="costo" name="costo" value="' . $row['costo'] . '" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="' . $row['fecha'] . '" required>
            </div>
        </form>';
    } else {
        echo '<p>No se encontraron datos para esta entrada.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Error al obtener los datos: ' . $e->getMessage() . '</p>';
}
?>
