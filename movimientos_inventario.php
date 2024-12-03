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
        <!-- botón de regreso al inicio -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="home.php" class="btn btn-primary">Regresar</a>
        </div>

        <!-- Botones para consultas -->
        <div class="text-center mt-4">
            <button onclick="showContent('saldos')" class="btn btn-info">Saldos</button>
            <button onclick="showContent('entradas')" class="btn btn-success">Entradas</button>
            <button onclick="showContent('salidas')" class="btn btn-danger">Salidas</button>
            <button onclick="showContent('movimientos')" class="btn btn-warning">Movimientos</button>
        </div>

        <!-- Área de contenido dinámico -->
        <div id="content-area" class="content-area mt-3">
            <p>Selecciona una opción para ver los detalles.</p>
        </div>
    </div>

    <script>
       // Función para cargar contenido dinámico en función del tipo de consulta
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

// Función para cargar el formulario de entrada
    function loadAddEntryForm() {
    $.get('add_entradas.php', function(data) {
        Swal.fire({
            title: 'Añadir Nueva Entrada',
            html: data,
            showCancelButton: true,
            confirmButtonText: 'Guardar Entrada',
            preConfirm: () => {
                const categoria = $('#categoria').val();
                const producto = $('#producto').val();
                const factura = $('#factura').val();
                const proveedor = $('#proveedor').val();
                const cantidad = $('#cantidad').val();
                const costo = $('#costo').val();
                const fecha = $('#fecha').val();

                if (!categoria || !producto || !factura || !proveedor || !cantidad || !costo || !fecha) {
                    Swal.showValidationMessage('Por favor llena todos los campos');
                    return false;
                }

                const data = {
                    categoria: categoria,
                    producto: producto,
                    factura: factura,
                    proveedor: proveedor,
                    cantidad: cantidad,
                    costo: costo,
                    fecha: fecha,
                    action: 'add',
                    type: 'entrada'
                };

                return data;
            }
            // Resto de la configuración
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'movimiento_handler.php',
                    data: result.value,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: response.success ? 'Éxito' : 'Error',
                            text: response.message,
                            icon: response.success ? 'success' : 'error'
                        }).then(() => {
                            if (response.success) {
                                location.reload();  // Recargar la página para mostrar la nueva entrada
                            }
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Hubo un problema al enviar los datos.', 'error');
                    }
                });
            }
        });
    });
}

function modifyRecord(type, id) {
    $.get(`edit_entradas.php?id=${id}`, function(data) {
        Swal.fire({
            title: 'Editar Entrada',
            html: data,
            showCancelButton: true,
            confirmButtonText: 'Guardar Cambios',
            preConfirm: () => {
                const categoria = $('#categoria').val();
                const producto = $('#producto').val();
                const factura = $('#factura').val();
                const proveedor = $('#proveedor').val();
                const cantidad = $('#cantidad').val();
                const costo = $('#costo').val();
                const fecha = $('#fecha').val();

                if (!categoria || !producto || !factura || !proveedor || !cantidad || !costo || !fecha) {
                    Swal.showValidationMessage('Por favor llena todos los campos');
                    return false;
                }

                const data = {
                    id: id,
                    categoria: categoria,
                    producto: producto,
                    factura: factura,
                    proveedor: proveedor,
                    cantidad: cantidad,
                    costo: costo,
                    fecha: fecha,
                    action: 'edit',
                    type: 'entrada'
                };

                return data;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'movimiento_handler.php',
                    data: result.value,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: response.success ? 'Éxito' : 'Error',
                            text: response.message,
                            icon: response.success ? 'success' : 'error'
                        }).then(() => {
                            if (response.success) {
                                location.reload();  // Recargar la página para mostrar los cambios
                            }
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Hubo un problema al enviar los datos.', 'error');
                    }
                });
            }
        });
    });
}

function removeEntry(type, id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('movimiento_handler.php', {
                action: 'delete',
                id: id,
                type: type
            }, function(response) {
                Swal.fire({
                    title: response.success ? 'Éxito' : 'Error',
                    text: response.message,
                    icon: response.success ? 'success' : 'error'
                }).then(() => {
                    if (response.success) {
                        location.reload();  // Recargar la página para mostrar los cambios
                    }
                });
            }, 'json');
        }
    });
}

//funncionalidad para la salida de materiales
function loadAddSalidaForm() {
    $.get('add_salidas.php', function(data) {
        Swal.fire({
            title: 'Añadir Nueva Salida',
            html: data,
            showCancelButton: true,
            confirmButtonText: 'Guardar Salida',
            preConfirm: () => {
                const categoria = $('#categoria').val();
                const producto = $('#producto').val();
                const folio = $('#folio').val();
                const supervisor = $('#supervisor').val();
                const colonia = $('#colonia').val();
                const calle = $('#calle').val();
                const usuario = $('#usuario').val();
                const contrato = $('#contrato').val();
                const medidor = $('#medidor').val();
                const cantidad = $('#cantidad').val();
                const costo = $('#costo').val();
                const fecha = $('#fecha').val();
                
                if (!categoria || !producto || !folio || !supervisor || !colonia || !calle || !usuario || !contrato || !medidor || !cantidad || !costo || !fecha) {
                    Swal.showValidationMessage('Por favor llena todos los campos');
                    return false;
                }

                const data = {
                    categoria: categoria,
                    producto: producto,
                    folio: folio,
                    supervisor: supervisor,
                    colonia: colonia,
                    calle: calle,
                    usuario: usuario,
                    contrato: contrato,
                    medidor: medidor,
                    cantidad: cantidad,
                    costo: costo,
                    fecha: fecha,
                    action: 'add',
                    type: 'salida'
                };

                return data;
            }
            // Resto de la configuración
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'salidas_handler.php',
                    data: result.value,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: response.success ? 'Éxito' : 'Error',
                            text: response.message,
                            icon: response.success ? 'success' : 'error'
                        }).then(() => {
                            if (response.success) {
                                location.reload();  // Recargar la página para mostrar la nueva salida
                            }
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Hubo un problema al enviar los datos.', 'error');
                    }
                });
            }
        });
    });
}

//funcionalidad para la edicion de salidas
function editEntry(type, id) {
    if (type === 'salida') {
        $.get(`edit_salidas.php?id=${id}`, function(data) {
            Swal.fire({
                title: 'Editar Salida',
                html: data,
                showCancelButton: true,
                confirmButtonText: 'Guardar Cambios',
                preConfirm: () => {
                    const categoria = $('#categoria').val();
                    const producto = $('#producto').val();
                    const folio = $('#folio').val();
                    const supervisor = $('#supervisor').val();
                    const colonia = $('#colonia').val();
                    const calle = $('#calle').val();
                    const usuario = $('#usuario').val();
                    const contrato = $('#contrato').val();
                    const medidor = $('#medidor').val();
                    const cantidad = $('#cantidad').val();
                    const costo = $('#costo').val();
                    const fecha = $('#fecha').val();
                    
                    if (!categoria || !producto || !folio || !supervisor || !colonia || !calle || !usuario || !contrato || !medidor || !cantidad || !costo || !fecha) {
                        Swal.showValidationMessage('Por favor llena todos los campos');
                        return false;
                    }

                    const data = {
                        id: id,
                        categoria: categoria,
                        producto: producto,
                        folio: folio,
                        supervisor: supervisor,
                        colonia: colonia,
                        calle: calle,
                        usuario: usuario,
                        contrato: contrato,
                        medidor: medidor,
                        cantidad: cantidad,
                        costo: costo,
                        fecha: fecha,
                        action: 'edit',
                        type: 'salida'
                    };

                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'salidas_handler.php',
                        data: result.value,
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                title: response.success ? 'Éxito' : 'Error',
                                text: response.message,
                                icon: response.success ? 'success' : 'error'
                            }).then(() => {
                                if (response.success) {
                                    location.reload();  // Recargar la página para mostrar los cambios
                                }
                            });
                        },
                        error: function() {
                            Swal.fire('Error', 'Hubo un problema al enviar los datos.', 'error');
                        }
                    });
                }
            });
        });
    }
}

function deleteEntry(type, id) {
    if (type === 'salida') {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('salidas_handler.php', {
                    action: 'delete',
                    id: id,
                    type: type
                }, function(response) {
                    Swal.fire({
                        title: response.success ? 'Éxito' : 'Error',
                        text: response.message,
                        icon: response.success ? 'success' : 'error'
                    }).then(() => {
                        if (response.success) {
                            location.reload();  // Recargar la página para mostrar los cambios
                        }
                    });
                }, 'json');
            }
        });
    }
}



</script>

</body>

</html>