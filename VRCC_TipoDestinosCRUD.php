<?php
$filename = 'tipos_destinos.json';

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

$tipos_destinos = load_data($filename);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];

    if ($accion == 'crear') {
        $new_id = end($tipos_destinos)['id'] + 1;
        $nuevo_tipo_destino = [
            'id' => $new_id,
            'tipo' => $_POST['tipo']
        ];
        $tipos_destinos[] = $nuevo_tipo_destino;
        save_data($filename, $tipos_destinos);
    } elseif ($accion == 'actualizar') {
        foreach ($tipos_destinos as &$tipo_destino) {
            if ($tipo_destino['id'] == $_POST['id']) {
                $tipo_destino['tipo'] = $_POST['tipo'];
                break;
            }
        }
        save_data($filename, $tipos_destinos);
    } elseif ($accion == 'eliminar') {
        $tipos_destinos = array_filter($tipos_destinos, function($tipo_destino) {
            return $tipo_destino['id'] != $_POST['id'];
        });
        save_data($filename, $tipos_destinos);
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
    <title>Dashboard de Tipos de Destinos</title>
</head>
<body>
    <header>
        <h1>Dashboard de Tipos de Destinos</h1>
    </header>

    <h2>Formulario de Tipo de Destino</h2>
    <form method="post" action="">
        <input type="hidden" name="accion" value="crear">

        <label for="tipo">Tipo de Destino:</label>
        <input type="text" id="tipo" name="tipo" required>

        <input type="submit" value="Guardar">
    </form>

    <h2>Tipos de Destinos Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tipos_destinos as $tipoDestino): ?>
                <tr>
                    <td><?= htmlspecialchars($tipoDestino['id']) ?></td>
                    <td><?= htmlspecialchars($tipoDestino['tipo']) ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($tipoDestino['id']) ?>">
                            <input type="text" name="tipo" required value="<?= htmlspecialchars($tipoDestino['tipo']) ?>">
                            <input type="submit" value="Actualizar">
                        </form>
                        <form method="post" action="">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($tipoDestino['id']) ?>">
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
