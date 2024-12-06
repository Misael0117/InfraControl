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
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="home.php" class="btn btn-primary">Regresar</a>
        </div>

        <div class="text-center mt-4">
            <button onclick="showContent('productos')" class="btn btn-success">Productos</button>
            <button onclick="showContent('proveedores')" class="btn btn-danger">Proveedores</button>
        </div>

        <div id="content-area" class="content-area mt-3">
            <p>Selecciona una opción para ver los detalles.</p>
        </div>
    </div>

    <script>
       function showContent(type) {
        const contentArea = document.getElementById("content-area");

        let url = "";
        if (type === "productos") {
            url = "get_producto.php";
        } else if (type === "proveedores") {
            url = "get_proveedor.php";
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

    function loadAddProductForm() {
        $.get('add_producto.php', function(data) {
            Swal.fire({
                title: 'Añadir Nuevo Producto',
                html: data,
                showCancelButton: true,
                confirmButtonText: 'Guardar Producto',
                preConfirm: () => {
                    const clave = $('#clave').val();
                    const producto = $('#producto').val();
                    const categoria = $('#categoria').val();

                    if (!clave || !producto || !categoria) {
                        Swal.showValidationMessage('Por favor llena todos los campos');
                        return false;
                    }

                    const data = {
                        clave: clave,
                        producto: producto,
                        categoria: categoria,
                        action: 'add',
                        type: 'producto'
                    };

                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'movimiento_catalago.php',
                        data: result.value,
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                title: response.success ? 'Éxito' : 'Error',
                                text: response.message,
                                icon: response.success ? 'success' : 'error'
                            }).then(() => {
                                if (response.success) {
                                    location.reload();  
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

    function modifyProduct(id) {
        $.get(`edit_producto.php?id=${id}`, function(data) {
            Swal.fire({
                title: 'Editar Producto',
                html: data,
                showCancelButton: true,
                confirmButtonText: 'Guardar Cambios',
                preConfirm: () => {
                    const clave = $('#clave').val();
                    const producto = $('#producto').val();
                    const categoria = $('#categoria').val();

                    if (!clave || !producto || !categoria) {
                        Swal.showValidationMessage('Por favor llena todos los campos');
                        return false;
                    }

                    const data = {
                        id: id,
                        clave: clave,
                        producto: producto,
                        categoria: categoria,
                        action: 'edit',
                        type: 'producto'
                    };

                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'movimiento_catalago.php',
                        data: result.value,
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                title: response.success ? 'Éxito' : 'Error',
                                text: response.message,
                                icon: response.success ? 'success' : 'error'
                            }).then(() => {
                                if (response.success) {
                                    location.reload();  
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

    function removeProduct(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('movimiento_proveedor.php', {
                    action: 'delete',
                    id: id,
                    type: 'producto'
                }, function(response) {
                    Swal.fire({
                        title: response.success ? 'Éxito' : 'Error',
                        text: response.message,
                        icon: response.success ? 'success' : 'error'
                    }).then(() => {
                        if (response.success) {
                            location.reload();  
                        }
                    });
                }, 'json');
            }
        });
    }


// Función para cargar el formulario de añadir proveedor
function loadAddProveedorForm() {
    $.get('add_proveedor.php', function(data) {
        Swal.fire({
            title: 'Añadir Nuevo Proveedor',
            html: data,
            showCancelButton: true,
            confirmButtonText: 'Guardar Proveedor',
            preConfirm: () => {
                const proveedor = $('#proveedor').val();
                if (!proveedor) {
                    Swal.showValidationMessage('Por favor llena todos los campos');
                    return false;
                }
                return {
                    proveedor: proveedor,
                    action: 'add',
                    type: 'proveedor'
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'movimiento_proveedor.php',
                    data: result.value,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: response.success ? 'Éxito' : 'Error',
                            text: response.message,
                            icon: response.success ? 'success' : 'error'
                        }).then(() => {
                            if (response.success) {
                                location.reload();
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


// Función para editar un proveedor
function modifyProveedor(id) {
    $.get(`edit_proveedor.php?id=${id}`, function(data) {
        Swal.fire({
            title: 'Editar Proveedor',
            html: data,
            showCancelButton: true,
            confirmButtonText: 'Guardar Cambios',
            preConfirm: () => {
                const proveedor = $('#proveedor').val();
                if (!proveedor) {
                    Swal.showValidationMessage('Por favor llena todos los campos');
                    return false;
                }
                return {
                    id: id,
                    proveedor: proveedor,
                    action: 'edit',
                    type: 'proveedor'
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'movimiento_proveedor.php',
                    data: result.value,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: response.success ? 'Éxito' : 'Error',
                            text: response.message,
                            icon: response.success ? 'success' : 'error'
                        }).then(() => {
                            if (response.success) {
                                location.reload();
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

// Función para eliminar un proveedor
function removeProveedor(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('movimiento_proveedor.php', {
                action: 'delete',
                id: id,
                type: 'proveedor'
            }, function(response) {
                Swal.fire({
                    title: response.success ? 'Éxito' : 'Error',
                    text: response.message,
                    icon: response.success ? 'success' : 'error'
                }).then(() => {
                    if (response.success) {
                        location.reload();
                    }
                });
            }, 'json');
        }
    });
}

    </script>
</body>
</html>
