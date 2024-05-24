<?php
$filename = 'transportes_terrestres.json';

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

$transportes = load_data($filename);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];

    if ($accion == 'crear') {
        $new_id = end($transportes)['id'] + 1;
        $nuevo_transporte = [
            'id' => $new_id,
            'capacidad' => $_POST['capacidad'],
            'anioFabricacion' => $_POST['anioFabricacion']
        ];
        $transportes[] = $nuevo_transporte;
        save_data($filename, $transportes);
    } elseif ($accion == 'actualizar') {
        foreach ($transportes as &$transporte) {
            if ($transporte['id'] == $_POST['id']) {
                $transporte['capacidad'] = $_POST['capacidad'];
                $transporte['anioFabricacion'] = $_POST['anioFabricacion'];
                break;
            }
        }
        save_data($filename, $transportes);
    } elseif ($accion == 'eliminar') {
        $transportes = array_filter($transportes, function($transporte) {
            return $transporte['id'] != $_POST['id'];
        });
        save_data($filename, $transportes);
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
    <title>Dashboard de Transportes Terrestres</title>
</head>
<body>
    <header>
        <h1>Dashboard de Transportes Terrestres</h1>
    </header>

    <h2>Formulario de Transporte Terrestre</h2>
    <form method="post" action="">
        <input type="hidden" name="accion" value="crear">

        <label for="capacidad">Capacidad de Pasajeros:</label>
        <input type="number" id="capacidad" name="capacidad" required min="1" max="80">

        <label for="anioFabricacion">Año de Fabricación:</label>
        <input type="number" id="anioFabricacion" name="anioFabricacion" required min="2000" max="2024">

        <input type="submit" value="Guardar">
    </form>

    <h2>Transportes Registrados</h2>
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
            <?php foreach ($transportes as $transporte): ?>
                <tr>
                    <td><?= htmlspecialchars($transporte['id']) ?></td>
                    <td><?= htmlspecialchars($transporte['capacidad']) ?></td>
                    <td><?= htmlspecialchars($transporte['anioFabricacion']) ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($transporte['id']) ?>">
                            <input type="number" name="capacidad" required min="1" max="80" value="<?= htmlspecialchars($transporte['capacidad']) ?>">
                            <input type="number" name="anioFabricacion" required min="2000" max="2024" value="<?= htmlspecialchars($transporte['anioFabricacion']) ?>">
                            <input type="submit" value="Actualizar">
                        </form>
                        <form method="post" action="">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($transporte['id']) ?>">
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
