<?php
session_start();
require 'config.php';  // Incluir el archivo de configuración para la conexión PDO

$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $IDUsuario = $_POST['IDUsuario'];
    $Nombre = $_POST['Nombre'];
    $ApellidoPaterno = $_POST['ApellidoPaterno'];
    $ApellidoMaterno = $_POST['ApellidoMaterno'];
    $Edad = $_POST['Edad'];
    $Sexo = $_POST['Sexo'];
    $Email = $_POST['Email'];
    $Telefono = $_POST['Telefono'];
    $Password = $_POST['Password'];
    $ConfirmPassword = $_POST['ConfirmPassword'];
    $TipoUsuario = $_POST['TipoUsuario'];

    // Validaciones del formulario
    if (empty($IDUsuario) || empty($Nombre) || empty($ApellidoPaterno) || empty($Edad) || empty($Sexo) || empty($Email) || empty($Password) || empty($ConfirmPassword)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Formato de email inválido.";
    }

    if ($Password !== $ConfirmPassword) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (strlen($Password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    // Verificar si el IDUsuario ya existe
    $stmt = $pdo->prepare("SELECT IDUsuario FROM usuarios WHERE IDUsuario = :IDUsuario");
    $stmt->execute(['IDUsuario' => $IDUsuario]);
    if ($stmt->fetch()) {
        $errores[] = "El IDUsuario ya existe.";
    }

    // Si no hay errores, insertar el usuario en la base de datos
    if (empty($errores)) {
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (IDUsuario, Nombre, ApellidoPaterno, ApellidoMaterno, Edad, Sexo, Email, Telefono, TipoUsuario, password) 
                               VALUES (:IDUsuario, :Nombre, :ApellidoPaterno, :ApellidoMaterno, :Edad, :Sexo, :Email, :Telefono, :TipoUsuario, :password)");

        $stmt->execute([
            'IDUsuario' => $IDUsuario,
            'Nombre' => $Nombre,
            'ApellidoPaterno' => $ApellidoPaterno,
            'ApellidoMaterno' => $ApellidoMaterno,
            'Edad' => $Edad,
            'Sexo' => $Sexo,
            'Email' => $Email,
            'Telefono' => $Telefono,
            'TipoUsuario' => $TipoUsuario,
            'password' => $hashedPassword
        ]);

        echo "<p style='color:green;'>Usuario registrado exitosamente</p>";
    } else {
        foreach ($errores as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Librería El Tesoro del Saber</title>
    <style>
        .form-container {
            width: 300px;
            margin: 0 auto;
            margin-top: 50px;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container form input, .form-container form select {
            margin-bottom: 10px;
            padding: 8px;
            font-size: 16px;
        }
        .form-container form button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Registrarse</h2>

    <form action="registrarse.php" method="post">
        <input type="text" name="IDUsuario" placeholder="ID Usuario" required>
        <input type="text" name="Nombre" placeholder="Nombre" required>
        <input type="text" name="ApellidoPaterno" placeholder="Apellido Paterno" required>
        <input type="text" name="ApellidoMaterno" placeholder="Apellido Materno">
        <input type="number" name="Edad" placeholder="Edad" required>
        <select name="Sexo" required>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
        </select>
        <input type="email" name="Email" placeholder="Email" required>
        <input type="text" name="Telefono" placeholder="Teléfono">
        <input type="text" name="TipoUsuario" placeholder="Tipo Usuario" required>
        <input type="password" name="Password" placeholder="Contraseña" required>
        <input type="password" name="ConfirmPassword" placeholder="Confirmar Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>
</div>

</body>
</html>

