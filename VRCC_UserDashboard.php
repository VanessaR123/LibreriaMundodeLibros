<?php
// Nombre del archivo JSON
$filename = 'users.json';

// Funciones CRUD
function loadUsers() {
    global $filename;
    $data = file_exists($filename) ? file_get_contents($filename) : '';
    return $data ? json_decode($data, true) : [];
}

function saveUsers($users) {
    global $filename;
    if (!file_put_contents($filename, json_encode($users, JSON_PRETTY_PRINT))) {
        throw new Exception("Error al guardar los datos en el archivo JSON.");
    }
}

function createUser($user) {
    $users = loadUsers();
    $users[] = $user;
    saveUsers($users);
}

function readUsers() {
    return loadUsers();
}

function updateUser($curp, $updatedUser) {
    $users = loadUsers();
    foreach ($users as $key => $user) {
        if ($user['curp'] == $curp) {
            $users[$key] = array_merge($user, $updatedUser);
            saveUsers($users);
            return;
        }
    }
    throw new Exception("Usuario no encontrado.");
}

function deleteUser($curp) {
    $users = loadUsers();
    foreach ($users as $key => $user) {
        if ($user['curp'] == $curp) {
            array_splice($users, $key, 1);
            saveUsers($users);
            return;
        }
    }
    throw new Exception("Usuario no encontrado.");
}

// Función de validación
function validateUser($nombre, $primerApellido, $segundoApellido, $curp, $rfc, $fechaRegistro) {
    if (!preg_match("/^[a-zA-Z\s]+$/", $nombre)) {
        throw new Exception("El nombre solo puede contener letras y espacios.");
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $primerApellido)) {
        throw new Exception("El primer apellido solo puede contener letras y espacios.");
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $segundoApellido)) {
        throw new Exception("El segundo apellido solo puede contener letras y espacios.");
    }
    if (strlen($curp) != 18) {
        throw new Exception("CURP debe tener exactamente 18 caracteres.");
    }
    if (strlen($rfc) != 13) {
        throw new Exception("RFC debe tener exactamente 13 caracteres.");
    }
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fechaRegistro)) {
        throw new Exception("La fecha de registro debe estar en formato AAAA-MM-DD.");
    }
}

// Procesamiento de la solicitud
$usuarios = [];
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? 'crear';
    $nombre = $_POST['nombre'] ?? '';
    $primerApellido = $_POST['primerApellido'] ?? '';
    $segundoApellido = $_POST['segundoApellido'] ?? '';
    $curp = $_POST['curp'] ?? '';
    $rfc = $_POST['rfc'] ?? '';
    $fechaRegistro = $_POST['fechaRegistro'] ?? '';

    try {
        validateUser($nombre, $primerApellido, $segundoApellido, $curp, $rfc, $fechaRegistro);

        if ($accion == 'crear') {
            createUser(['nombre' => $nombre, 'primerApellido' => $primerApellido, 'segundoApellido' => $segundoApellido, 'curp' => $curp, 'rfc' => $rfc, 'fechaRegistro' => $fechaRegistro]);
            $mensaje = "Usuario creado correctamente.";
        } elseif ($accion == 'actualizar') {
            updateUser($curp, ['nombre' => $nombre, 'primerApellido' => $primerApellido, 'segundoApellido' => $segundoApellido, 'rfc' => $rfc, 'fechaRegistro' => $fechaRegistro]);
            $mensaje = "Usuario actualizado correctamente.";
        } elseif ($accion == 'eliminar') {
            deleteUser($curp);
            $mensaje = "Usuario eliminado correctamente.";
        }

    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Mostrar la lista de usuarios
$usuarios = readUsers();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Usuarios</title>
</head>
<body>
    <header>
        <h1>Dashboard de Usuarios</h1>
    </header>

    <h2>Formulario de Usuario</h2>
    <form method="post" action="">
        <input type="hidden" name="accion" value="crear">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="primerApellido">Primer Apellido:</label>
        <input type="text" id="primerApellido" name="primerApellido" required>

        <label for="segundoApellido">Segundo Apellido:</label>
        <input type="text" id="segundoApellido" name="segundoApellido" required>

        <label for="curp">CURP:</label>
        <input type="text" id="curp" name="curp" required>

        <label for="rfc">RFC:</label>
        <input type="text" id="rfc" name="rfc" required>

        <label for="fechaRegistro">Fecha de Registro:</label>
        <input type="date" id="fechaRegistro" name="fechaRegistro" required>

        <input type="submit" value="Guardar">
    </form>

    <?php if ($mensaje): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <h2>Usuarios Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>CURP</th>
                <th>RFC</th>
                <th>Fecha de Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['primerApellido']) ?></td>
                    <td><?= htmlspecialchars($usuario['segundoApellido']) ?></td>
                    <td><?= htmlspecialchars($usuario['curp']) ?></td>
                    <td><?= htmlspecialchars($usuario['rfc']) ?></td>
                    <td><?= htmlspecialchars($usuario['fechaRegistro']) ?></td>
                    <td>
                        <form method="post" action="" style="display:inline-block;">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="curp" value="<?= htmlspecialchars($usuario['curp']) ?>">
                            <input type="submit" value="Actualizar">
                        </form>
                        <form method="post" action="" style="display:inline-block;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="curp" value="<?= htmlspecialchars($usuario['curp']) ?>">
                            <input type="submit" value="Eliminar">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

