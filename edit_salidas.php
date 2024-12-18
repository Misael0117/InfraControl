<?php
include 'config.php';

$id = $_GET['id'];

try {
    $query = "SELECT * FROM salida_material WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo '
        <form id="form-edit-salida">
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" class="form-control" id="categoria" name="categoria" value="' . $row['categoria'] . '" required>
            </div>
            <div class="form-group">
                <label for="producto">Producto:</label>
                <input type="text" class="form-control" id="producto" name="producto" value="' . $row['producto'] . '" required>
            </div>
            <div class="form-group">
                <label for="folio">Folio:</label>
                <input type="text" class="form-control" id="folio" name="folio" value="' . $row['folio'] . '" required>
            </div>
            <div class="form-group">
                <label for="supervisor">Supervisor:</label>
                <input type="text" class="form-control" id="supervisor" name="supervisor" value="' . $row['supervisor'] . '" required>
            </div>
            <div class="form-group">
                <label for="colonia">Colonia:</label>
                <input type="text" class="form-control" id="colonia" name="colonia" value="' . $row['colonia'] . '" required>
            </div>
            <div class="form-group">
                <label for="calle">Calle:</label>
                <input type="text" class="form-control" id="calle" name="calle" value="' . $row['calle'] . '" required>
            </div>
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="' . $row['usuario'] . '" required>
            </div>
            <div class="form-group">
                <label for="contrato">Contrato:</label>
                <input type="text" class="form-control" id="contrato" name="contrato" value="' . $row['contrato'] . '" required>
            </div>
            <div class="form-group">
                <label for="medidor">Medidor:</label>
                <input type="text" class="form-control" id="medidor" name="medidor" value="' . $row['medidor'] . '" required>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="text" class="form-control" id="cantidad" name="cantidad" value="' . $row['cantidad'] . '" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="costo">Costo:</label>
                <input type="text" class="form-control" id="costo" name="costo" value="' . $row['costo'] . '" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha de Salida:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="' . $row['fecha'] . '" required>
            </div>
        </form>';
    } else {
        echo '<p>No se encontraron datos para esta salida.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Error al obtener los datos: ' . $e->getMessage() . '</p>';
}
?>
