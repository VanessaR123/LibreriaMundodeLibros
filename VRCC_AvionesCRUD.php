<?php
$filename = 'aviones.json';

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

$aviones = load_data($filename);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];

    if ($accion == 'crear') {
        $new_id = end($aviones)['id'] + 1;
        $nuevo_avion = [
            'id' => $new_id,
            'capacidad' => $_POST['capacidad'],
            'anioFabricacion' => $_POST['anioFabricacion']
        ];
        $aviones[] = $nuevo_avion;
        save_data($filename, $aviones);
    } elseif ($accion == 'actualizar') {
        foreach ($aviones as &$avion) {
            if ($avion['id'] == $_POST['id']) {
                $avion['capacidad'] = $_POST['capacidad'];
                $avion['anioFabricacion'] = $_POST['anioFabricacion'];
                break;
            }
        }
        save_data($filename, $aviones);
    } elseif ($accion == 'eliminar') {
        $aviones = array_filter($aviones, function($avion) {
            return $avion['id'] != $_POST['id'];
        });
        save_data($filename, $aviones);
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
    <title>Dashboard de Aviones</title>
</head>
<body>
    <header>
        <h1>Dashboard de Aviones</h1>
    </header>

    <h2>Formulario de Avión</h2>
    <form method="post" action="">
        <input type="hidden" name="accion" value="crear">

        <label for="capacidad">Capacidad:</label>
        <input type="number" id="capacidad" name="capacidad" required min="1" max="80">

        <label for="anioFabricacion">Año de Fabricación:</label>
        <input type="number" id="anioFabricacion" name="anioFabricacion" required min="2000" max="2024">

        <input type="submit" value="Guardar">
    </form>

    <h2>Aviones Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Capacidad</th>
                <th>Año de Fabricación</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aviones as $avion): ?>
                <tr>
                    <td><?= htmlspecialchars($avion['id']) ?></td>
                    <td><?= htmlspecialchars($avion['capacidad']) ?></td>
                    <td><?= htmlspecialchars($avion['anioFabricacion']) ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($avion['id']) ?>">
                            <input type="number" name="capacidad" required min="1" max="80" value="<?= htmlspecialchars($avion['capacidad']) ?>">
                            <input type="number" name="anioFabricacion" required min="2000" max="2024" value="<?= htmlspecialchars($avion['anioFabricacion']) ?>">
                            <input type="submit" value="Actualizar">
                        </form>
                        <form method="post" action="">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($avion['id']) ?>">
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
