<?php 
session_start(); 
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
include 'user_info.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InfraControl | Consultas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/consultas.css" rel="stylesheet">
    <!-- Incluir jQuery --> 
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <!-- Incluir SweetAlert2 --> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <!-- Información del usuario y botón de regreso al inicio -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="home.php" class="btn btn-primary">Regresar</a>
        </div>

        <!-- Botones para consultas -->
        <div class="text-center mt-4">
            <button onclick="showContent('saldos')" class="btn btn-info">Ver Saldos</button>
            <button onclick="showContent('entradas')" class="btn btn-success">Ver Entradas</button>
            <button onclick="showContent('salidas')" class="btn btn-danger">Ver Salidas</button>
            <button onclick="showContent('movimientos')" class="btn btn-warning">Ver Movimientos</button>
        </div>

        <!-- Área de contenido dinámico -->
        <div id="content-area" class="content-area mt-3">
            <p>Selecciona una opción para ver los detalles.</p>
        </div>
    </div>

    <script>
    function showContent(type) {
        const contentArea = document.getElementById("content-area");

        let url = "";
        if (type === "saldos") {
            url = "get_saldos.php"; 
        } else if (type === "entradas") {
            url = "get_entradas.php"; 
        } else if (type === "salidas") {
            url = "get_salidas.php"; 
        } else if (type === "movimientos") {
            url = "get_movimientos.php"; 
        }

        if (url) {
            $.ajax({
                url: url,
                method: "GET",
                success: function(response) {
                    contentArea.innerHTML = response;
                },
                error: function() {
                    contentArea.innerHTML = "<p>Error al cargar los datos.</p>";
                }
            });
        }
    }

    function addEntry(type) {
        let htmlContent = `
            <input id="input-categoria" class="swal2-input" placeholder="Categoría">
            <input id="input-producto" class="swal2-input" placeholder="Producto">
            <input id="input-producto_id" class="swal2-input" placeholder="Producto ID">
            <input id="input-product_id" class="swal2-input" placeholder="Product ID">
            <input id="input-factura" class="swal2-input" placeholder="Factura">
            <input id="input-proveedor" class="swal2-input" placeholder="Proveedor">
            <input id="input-cantidad" class="swal2-input" placeholder="Cantidad">
            <input id="input-costo" class="swal2-input" placeholder="Costo">
            <input id="input-fecha" class="swal2-input" placeholder="Fecha (YYYY-MM-DD)">
        `;

        if (type === 'salida') {
            htmlContent += `
                <input id="input-verducto" class="swal2-input" placeholder="Verducto">
                <input id="input-fecha_salida" class="swal2-input" placeholder="Fecha de Salida (YYYY-MM-DD)">
                <input id="input-folio" class="swal2-input" placeholder="Folio">
                <input id="input-supervisor" class="swal2-input" placeholder="Supervisor">
                <input id="input-colonia" class="swal2-input" placeholder="Colonia">
                <input id="input-calle" class="swal2-input" placeholder="Calle">
                <input id="input-usuario" class="swal2-input" placeholder="Usuario">
                <input id="input-contrato" class="swal2-input" placeholder="Contrato">
                <input id="input-medidor" class="swal2-input" placeholder="Medidor">
            `;
        }

        Swal.fire({
            title: `Añadir Nueva ${type === 'entrada' ? 'Entrada' : 'Salida'}`,
            html: htmlContent,
            showCancelButton: true,
            confirmButtonText: 'Añadir',
            preConfirm: () => {
                const categoria = $('#input-categoria').val();
                const producto = $('#input-producto').val();
                const producto_id = $('#input-producto_id').val();
                const product_id = $('#input-product_id').val();
                const factura = $('#input-factura').val();
                const proveedor = $('#input-proveedor').val();
                const cantidad = $('#input-cantidad').val();
                const costo = $('#input-costo').val();
                const fecha = $('#input-fecha').val();
                
                if (!categoria || !producto || !producto_id || !product_id || !factura || !proveedor || !cantidad || !costo || !fecha) {
                    Swal.showValidationMessage('Por favor llena todos los campos');
                }
                
                let data = { categoria, producto, producto_id, product_id, factura, proveedor, cantidad, costo, fecha };
                
                if (type === 'salida') {
                    const verducto = $('#input-verducto').val();
                    const fecha_salida = $('#input-fecha_salida').val();
                    const folio = $('#input-folio').val();
                    const supervisor = $('#input-supervisor').val();
                    const colonia = $('#input-colonia').val();
                    const calle = $('#input-calle').val();
                    const usuario = $('#input-usuario').val();
                    const contrato = $('#input-contrato').val();
                    const medidor = $('#input-medidor').val();
                    
                    if (!verducto || !fecha_salida || !folio || !supervisor || !colonia || !calle || !usuario || !contrato || !medidor) {
                        Swal.showValidationMessage('Por favor llena todos los campos adicionales para salida');
                    }
                    
                    data = { ...data, verducto, fecha_salida, folio, supervisor, colonia, calle, usuario, contrato, medidor };
                }
                
                return data;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('movimiento_handler.php', {
                    action: 'add',
                    type: type,
                    ...result.value
                }, function(response) {
                    Swal.fire(response.message);
                    showContent(type === 'entrada' ? 'entradas' : 'salidas');
                }, 'json');
            }
        });
    }

    function editEntry(type, id) {
        let htmlContent = `
            <input id="edit-categoria" class="swal2-input" placeholder="Categoría">
            <input id="edit-producto" class="swal2-input" placeholder="Producto">
            <input id="edit-producto_id" class="swal2-input" placeholder="Producto ID">
            <input id="edit-product_id" class="swal2-input" placeholder="Product ID">
            <input id="edit-factura" class="swal2-input" placeholder="Factura">
            <input id="edit-proveedor" class="swal2-input" placeholder="Proveedor">
            <input id="edit-cantidad" class="swal2-input" placeholder="Cantidad">
            <input id="edit-costo" class="swal2-input" placeholder="Costo">
            <input id="edit-fecha" class="swal2-input" placeholder="Fecha (YYYY-MM-DD)">
        `;

        if (type === 'salida') {
            htmlContent += `
                <input id="edit-verducto" class="swal2-input" placeholder="Verducto">
                <input id="edit-fecha_salida" class="swal2-input" placeholder="Fecha de Salida (YYYY-MM-DD)">
                <input id="edit-folio" class="swal2-input" placeholder="Folio">
                <input id="edit-supervisor" class="swal2-input" placeholder="Supervisor">
                <input id="edit-colonia" class="swal2-input" placeholder="Colonia">
                <input id="edit-calle" class="swal2-input" placeholder="Calle">
                <input id="edit-usuario" class="swal2-input" placeholder="Usuario">
                <input id="edit-contrato" class="swal2-input" placeholder="Contrato">
                <input id="edit-medidor" class="swal2-input" placeholder="Medidor">
            `;
        }

        Swal.fire({
            title: `Editar ${type === 'entrada' ? 'Entrada' : 'Salida'}`,
            html: htmlContent,
            showCancelButton: true,
            confirmButtonText: 'Guardar Cambios',
            preConfirm: () => {
                const categoria = $('#edit-categoria').val();
                const producto = $('#edit-producto').val();
                const producto_id = $('#edit-producto_id').val();
                const product_id = $('#edit-product_id').val();
                const factura = $('#edit-factura').val();
                const proveedor = $('#edit-proveedor').val();
                const cantidad = $('#edit-cantidad').val();
                const costo = $('#edit-costo').val();
                const fecha = $('#edit-fecha').val();

                if (!categoria || !producto || !producto_id || !product_id || !factura || !proveedor || !cantidad || !costo || !fecha) {
                    Swal.showValidationMessage('Por favor llena todos los campos');
                }

                let data = { categoria, producto, producto_id, product_id, factura, proveedor, cantidad, costo, fecha };

                if (type === 'salida') {
                    const verducto = $('#edit-verducto').val();
                    const fecha_salida = $('#edit-fecha_salida').val();
                    const folio = $('#edit-folio').val();
                    const supervisor = $('#edit-supervisor').val();
                    const colonia = $('#edit-colonia').val();
                    const calle = $('#edit-calle').val();
                    const usuario = $('#edit-usuario').val();
                    const contrato = $('#edit-contrato').val();
                    const medidor = $('#edit-medidor').val();

                    if (!verducto || !fecha_salida || !folio || !supervisor || !colonia || !calle || !usuario || !contrato || !medidor) {
                        Swal.showValidationMessage('Por favor llena todos los campos adicionales para salida');
                    }

                    data = { ...data, verducto, fecha_salida, folio, supervisor, colonia, calle, usuario, contrato, medidor };
                }

                return data;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('movimiento_handler.php', {
                    action: 'edit',
                    type: type,
                    id: id,
                    ...result.value
                }, function(response) {
                    Swal.fire(response.message);
                    showContent(type === 'entrada' ? 'entradas' : 'salidas');
                }, 'json');
            }
        });
    }

    function deleteEntry(type, id) {
        Swal.fire({
            title: `¿Estás seguro de eliminar esta ${type === 'entrada' ? 'Entrada' : 'Salida'}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('movimiento_handler.php', {
                    action: 'delete',
                    type: type,
                    id: id
                }, function(response) {
                    Swal.fire(response.message);
                    showContent(type === 'entrada' ? 'entradas' : 'salidas');
                }, 'json');
            }
        });
    }
        
</script>

</body>
</html>