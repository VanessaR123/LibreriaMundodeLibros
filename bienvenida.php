<?php
// bienvenida.php
session_start();

if (!isset($_SESSION['IDUsuario'])) {
    header("Location: login.php");
    exit();
}

$nombreCompleto = htmlspecialchars($_SESSION['Nombre'] . " " . $_SESSION['ApellidoPaterno'] . " " . $_SESSION['ApellidoMaterno']);
$tipoUsuario = htmlspecialchars($_SESSION['TipoUsuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida - Librería El Tesoro del Saber</title>
</head>
<body>

<nav>
    <a href="index.html">Inicio</a>
    <a href="consultar.php">Consultar</a>
    <a href="registrar.php">Registrar</a>
    <?php if ($tipoUsuario == 'PL'): ?>
        <a href="modificar.php">Modificar</a>
        <a href="eliminar.php">Eliminar</a>
    <?php endif; ?>
    <a href="logout.php">Salir</a>
</nav>

<div class="container">
    <h1>¡Bienvenido <?php echo $nombreCompleto; ?>!</h1>
    <p>¡Has ingresado como <?php echo $tipoUsuario; ?>!</p>
</div>

</body>
</html>


