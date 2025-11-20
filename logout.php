<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Eliminar cookies
if (isset($_COOKIE['user_email'])) {
    setcookie('user_email', '', time() - 3600, "/");
}

// Redirigir al login
header("Location: login.php");
exit();
?>
