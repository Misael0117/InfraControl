<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Formulario para añadir nueva salida -->
    <form id="form-nueva-salida">
        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <input type="text" class="form-control" id="categoria" name="categoria" required>
        </div>
        <div class="form-group">
            <label for="producto">Producto:</label>
            <input type="text" class="form-control" id="producto" name="producto" required>
        </div>
        <div class="form-group">
            <label for="folio">Folio:</label>
            <input type="text" class="form-control" id="folio" name="folio" required>
        </div>
        <div class="form-group">
            <label for="supervisor">Supervisor:</label>
            <input type="text" class="form-control" id="supervisor" name="supervisor" required>
        </div>
        <div class="form-group">
            <label for="colonia">Colonia:</label>
            <input type="text" class="form-control" id="colonia" name="colonia" required>
        </div>
        <div class="form-group">
            <label for="calle">Calle:</label>
            <input type="text" class="form-control" id="calle" name="calle" required>
        </div>
        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
        </div>
        <div class="form-group">
            <label for="contrato">Contrato:</label>
            <input type="text" class="form-control" id="contrato" name="contrato" required>
        </div>
        <div class="form-group">
            <label for="medidor">Medidor:</label>
            <input type="text" class="form-control" id="medidor" name="medidor" required>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="text" class="form-control" id="cantidad" name="cantidad" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="costo">Costo:</label>
            <input type="text" class="form-control" id="costo" name="costo" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="fecha">Fecha de Salida:</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>
    </form>

</body>
</html>