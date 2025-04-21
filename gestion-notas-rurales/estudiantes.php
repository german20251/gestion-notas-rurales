<?php
include("db.php");
include("notificar.php"); // <-- Se incluye la función de notificaciones
$mensaje = "";

// Crear
if (isset($_POST['crear'])) {
    $nombre = $_POST['nombre'];
    if ($conexion->query("INSERT INTO estudiantes (nombre_estudiante) VALUES ('$nombre')")) {
        $mensaje = "Estudiante creado exitosamente.";
        registrarNotificacion("Se creó el estudiante: $nombre");
    }
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    if ($conexion->query("UPDATE estudiantes SET nombre_estudiante='$nombre' WHERE id=$id")) {
        $mensaje = "Estudiante actualizado exitosamente.";
        registrarNotificacion("Se actualizó el estudiante con ID $id a nombre: $nombre");
    }
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    if ($conexion->query("DELETE FROM estudiantes WHERE id=$id")) {
        $mensaje = "Estudiante eliminado exitosamente.";
        registrarNotificacion("Se eliminó el estudiante con ID: $id");
    }
}

// Editar
$editar = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $editar = $conexion->query("SELECT * FROM estudiantes WHERE id=$id")->fetch_assoc();
}
?>

<link rel="stylesheet" href="css/estilo.css">

<!-- CAMPANA DE NOTIFICACIONES -->
<?php
$notificaciones = $conexion->query("SELECT * FROM notificaciones ORDER BY fecha DESC LIMIT 5");
?>
<div style="position: absolute; top: 20px; right: 20px;">
    <div style="position: relative; display: inline-block;">
        <img src="img/campana.png" alt="Notificaciones" style="width: 30px; cursor: pointer;" onclick="document.getElementById('notifBox').classList.toggle('show')">
        <div id="notifBox" class="notif-dropdown">
            <?php while ($n = $notificaciones->fetch_assoc()): ?>
                <div class="notif-item"><?= $n['mensaje'] ?> <br><small><?= $n['fecha'] ?></small></div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<style>
Body {

background-color:#053c61
}
.notif-dropdown {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 250px;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 8px;
    overflow: hidden;
}
.notif-dropdown.show {
    display: block;
}
.notif-item {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}
.notif-item:last-child {
    border-bottom: none;
}
</style>

<!-- CONTENIDO PRINCIPAL -->
<div class="container">
<img src="images/logo.png" width="150px" align="right" style="margin-bottom: -30px; margin-top: -30px; " />
    <h2 style="color:#053c61; font-size:36px">Gestión de Estudiantes</h2>

    <!-- Mensaje de éxito -->
    <?php if ($mensaje): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; border-radius: 5px;">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return confirmarAccion(this);">
        <input type="hidden" name="id" value="<?= $editar['id'] ?? '' ?>">
        <label>Nombre del estudiante:</label>
        <input type="text" name="nombre" value="<?= $editar['nombre_estudiante'] ?? '' ?>" required>
        <div class="btns">
            <button type="submit" name="crear">Crear</button>
            <button type="submit" name="actualizar">Actualizar</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $resultado = $conexion->query("SELECT * FROM estudiantes");
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre_estudiante']}</td>
                    <td>
                        <a href='estudiantes.php?eliminar={$row['id']}' onclick='return confirmarEliminar();'>Eliminar</a>
                        <a href='estudiantes.php?editar={$row['id']}'>Editar</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Botón de volver centrado -->
    <div style="margin-top: 20px; text-align: center;">
        <a href="dashboard.php">
            <button style="padding: 10px 20px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Volver
            </button>
        </a>
    </div>
</div>

<!-- Scripts de confirmación -->
<script>
function confirmarEliminar() {
    return confirm("¿Estás seguro de que deseas eliminar este estudiante?");
}

function confirmarAccion(form) {
    if (form.actualizar && form.actualizar.name === "actualizar" && form.actualizar.clicked) {
        return confirm("¿Estás seguro de que deseas actualizar este estudiante?");
    }
    return true;
}

document.querySelectorAll("form button").forEach(btn => {
    btn.addEventListener("click", function () {
        if (this.name === "actualizar") {
            this.form.actualizar.clicked = true;
        }
    });
});
</script>
<footer style=" position: fixed; bottom: 0;  width: 100%;  background-color: #ffffff;   left: 0;
  color: black;  text-align: center;   padding: 5px 0;   font-family: 'Segoe UI', sans-serif;
  font-size: 14px; font-style: italic;"> -- Proyecto de Grado: Germán Correa & Omar Cortés --<br> Grupo: 202016907_82
</footer>
