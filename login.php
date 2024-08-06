<?php
// login.php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['IDUsuario'];
    $password = $_POST['Password'];

    // Consulta para verificar el usuario en la base de datos usando PDO y sentencias preparadas
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE IDUsuario = :IDUsuario");
    $stmt->execute(['IDUsuario' => $username]);
    $user = $stmt->fetch();

    // Validación de la contraseña en texto plano
    if ($user && $password === $user['Password']) {
        $_SESSION['IDUsuario'] = $user['IDUsuario'];
        $_SESSION['Nombre'] = $user['Nombre'];
        $_SESSION['ApellidoPaterno'] = $user['ApellidoPaterno'];
        $_SESSION['ApellidoMaterno'] = $user['ApellidoMaterno'];
        $_SESSION['TipoUsuario'] = $user['TipoUsuario'];

        // Redireccionar a la página de bienvenida
        header("Location: bienvenida.php");
        exit();
    } else {
        echo "<p style='color:red;'>Usuario no registrado o contraseña incorrecta</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Librería El Tesoro del Saber</title>
</head>
<body>

<nav>
    <a href="index.html">Inicio</a>
    <a href="registrarse.php">Registrarse</a>
    <a href="login.php">Iniciar sesión</a>
</nav>

<div class="form-container">
    <h2>Iniciar sesión</h2>
    <form action="login.php" method="post">
        <input type="text" name="IDUsuario" placeholder="ID Usuario" required>
        <input type="password" name="Password" placeholder="Password" required>
        <button type="submit">Iniciar sesión</button>
    </form>
</div>

</body>
</html>

