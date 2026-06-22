<?php
session_start(); // Retoma la sesión actual
session_unset(); // Borra todas las variables de sesión (usuario, rol, nombre)
session_destroy(); // Destruye la sesión por completo

// Redirige de vuelta a la pantalla de inicio de sesión
header("Location: login.php");
exit();
?>