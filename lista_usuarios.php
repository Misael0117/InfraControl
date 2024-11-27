<?php
require('config.php');

// Eliminar Usuario
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el usuario.']);
    }
    exit();
}

// Editar Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $user_level = $_POST['user_level'];
    $estado = $_POST['estado'];

    $sql = "UPDATE usuarios SET nombre = :nombre, username = :username, user_level = :user_level, estado = :estado WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':user_level', $user_level);
    $stmt->bindParam(':estado', $estado);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el usuario.']);
    }
    exit();
}

// Obtener datos del usuario para editar
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $id = $_GET['id'];
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="mt-3">
    <a href="configurar_usuarios.php" class="btn btn-secondary">Regresar</a>
</div>
<div class="container mt-5">
    <h2>Lista de Usuarios</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Nivel</th>
                <th>Estado</th>
                <th>Última Conexión</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="userTable">
            <?php
            $sql = "SELECT id, nombre, username, user_level, estado, last_connection FROM usuarios";
            $stmt = $conn->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $estado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';

                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['user_level'] . "</td>";
                echo "<td>" . $estado . "</td>";
                echo "<td>" . $row['last_connection'] . "</td>";
                echo "<td>";
                echo "<button class='btn btn-primary btn-sm' onclick='editUser(" . $row['id'] . ")'>Editar</button> ";
                echo "<button class='btn btn-danger btn-sm' onclick='deleteUser(" . $row['id'] . ")'>Eliminar</button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function editUser(id) {
    fetch('lista_usuarios.php?action=edit&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data) {
                Swal.fire({
                    title: 'Editar Usuario',
                    html: `
                        <input type="hidden" id="editUserId" value="${data.id}">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editNombre" value="${data.nombre}" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="editUsername" value="${data.username}" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserLevel" class="form-label">Nivel de Usuario</label>
                            <input type="number" class="form-control" id="editUserLevel" value="${data.user_level}" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEstado" class="form-label">Estado</label>
                            <select class="form-control" id="editEstado" required>
                                <option value="1" ${data.estado == 1 ? 'selected' : ''}>Activo</option>
                                <option value="0" ${data.estado == 0 ? 'selected' : ''}>Inactivo</option>
                            </select>
                        </div>
                    `,
                    focusConfirm: false,
                    preConfirm: () => {
                        return {
                            id: document.getElementById('editUserId').value,
                            nombre: document.getElementById('editNombre').value,
                            username: document.getElementById('editUsername').value,
                            user_level: document.getElementById('editUserLevel').value,
                            estado: document.getElementById('editEstado').value
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const data = result.value;
                        $.post('usuarios.php', {
                            id: data.id,
                            nombre: data.nombre,
                            username: data.username,
                            user_level: data.user_level,
                            estado: data.estado,
                            edit_user: 1
                        }, function(response) {
                            const res = JSON.parse(response);
                            if (res.status === 'success') {
                                Swal.fire('Actualizado', res.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        });
                    }
                });
            }
        });
}

function deleteUser(id) {
    Swal.fire({
        title: '¿Estás seguro de que deseas eliminar este usuario?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('lista_usuarios.php?action=delete&id=' + id, function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire('Eliminado', res.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>
</html>
