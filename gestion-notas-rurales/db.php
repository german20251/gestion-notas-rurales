<?php
$conexion = new mysqli("localhost", "root", "", "gestion_notas");
$conexion->set_charset("utf8mb4");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
