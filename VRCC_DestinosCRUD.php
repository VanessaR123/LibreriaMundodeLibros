<?php
$filename = 'destinos.json';

function load_data($filename) {
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        return json_decode($data, true);
    }
    return [];
}

function save_data($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

$destinos = load_data($filename);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];

    if ($accion == 'crear') {
        $new_id = end($destinos)['id'] + 1;
        $nuevo_destino = [
            'id' => $new_id,
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion']
        ];
        $destinos[] = $nuevo_destino;
        save_data($filename, $destinos);
    } elseif ($accion == 'actualizar') {
        foreach ($destinos as &$destino) {
            if ($destino['id'] == $_POST['id']) {
                $destino['nombre'] = $_POST['nombre'];
                $destino['descripcion'] = $_POST['descripcion'];
                break;
            }
        }
        save_data($filename, $destinos);
    } elseif ($accion == 'eliminar') {
        $destinos = array_filter($destinos, function($destino) {
            return $destino['id'] != $_POST['id'];
        });
        save_data($filename, $destinos);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Destinos</title>
</head>
<body>
    <header>
        <h1>Dashboard de Destinos</h1>
    </header>

    <h2>Formulario de Destino</h2>
    <form method="post" action="">
        <input type="hidden" name="accion" value="crear">

        <label for="nombre">Nombre del Destino:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" maxlength="1000"></textarea>

        <input type="submit" value="Guardar">
    </form>

    <h2>Destinos Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($destinos as $destino): ?>
                <tr>
                    <td><?= htmlspecialchars($destino['id']) ?></td>
                    <td><?= htmlspecialchars($destino['nombre']) ?></td>
                    <td><?= htmlspecialchars($destino['descripcion']) ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($destino['id']) ?>">
                            <input type="text" name="nombre" required value="<?= htmlspecialchars($destino['nombre']) ?>">
                            <textarea name="descripcion" maxlength="1000"><?= htmlspecialchars($destino['descripcion']) ?></textarea>
                            <input type="submit" value="Actualizar">
                        </form>
                        <form method="post" action="">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($destino['id']) ?>">
                            <input type="submit" value="Eliminar">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <footer>
        <p>VRCC – PW1 – 2024/04/30</p>
    </footer>
</body>
</html>
