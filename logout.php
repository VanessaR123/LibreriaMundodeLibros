<?php
// archivo: logout.php
session_start();
$_SESSION = []; // Limpiar todas las variables de sesión
session_destroy(); // Destruir la sesión
header("Location: index.html"); // Redireccionar a la página de inicio
exit();
?>
