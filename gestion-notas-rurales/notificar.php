<?php
include("db.php");

function registrarNotificacion($mensaje) {
    global $conexion;
    $stmt = $conexion->prepare("INSERT INTO notificaciones (mensaje) VALUES (?)");
    $stmt->bind_param("s", $mensaje);
    $stmt->execute();
}
?>
