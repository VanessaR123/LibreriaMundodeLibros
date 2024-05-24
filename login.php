<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start(); // Inicia una nueva sesión o reanuda la existente

// Variables para el administrador
$user_admin = "admin_vrcc"; // Usuario administrador con iniciales de nombre y apellido
$password_admin = "es172006657"; // Matrícula del administrador

// Variables para el cliente
$user_cliente = "cliente_vrcc"; // Usuario cliente con iniciales de nombre y apellido
$password_cliente = "es172006657"; // Matrícula del cliente

// Verificar si se recibieron datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verificar las credenciales del administrador
    if ($username === $user_admin && $password === $password_admin) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = "administrador";
        header("Location: VRCC_AdminDashboard.html"); // Asegúrarnos de que este archivo exista
        exit();
    }
    // Verificar las credenciales del cliente
    elseif ($username === $user_cliente && $password === $password_cliente) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = "cliente";
        header("Location: VRCC_UserDashboard.php"); // Asegúrarnos de que este archivo exista
        exit();
    }
    // Si las credenciales no coinciden
    else {
        echo "<p>Error de inicio de sesión: usuario o contraseña incorrectos.</p>";
    }
}
?>