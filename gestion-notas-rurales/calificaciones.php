<?php
include("db.php");
include("notificar.php"); // Para registrar notificaciones
$mensaje = "";

// Crear
if (isset($_POST['crear'])) {
    $estudiante_id = $_POST['estudiante_id'];
    $materia = $_POST['materia'];
    $nota = $_POST['nota'];
    if ($conexion->query("INSERT INTO calificaciones (estudiante_id, materia, nota) VALUES ($estudiante_id, '$materia', $nota)")) {
        $mensaje = "Registro creado exitosamente.";
        registrarNotificacion("Se registró una calificación de $nota en $materia para el estudiante con ID $estudiante_id");
    }
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $estudiante_id = $_POST['estudiante_id'];
    $materia = $_POST['materia'];
    $nota = $_POST['nota'];
    if ($conexion->query("UPDATE calificaciones SET estudiante_id=$estudiante_id, materia='$materia', nota=$nota WHERE id=$id")) {
        $mensaje = "Registro actualizado exitosamente.";
        registrarNotificacion("Se actualizó la calificación con ID $id a $nota en $materia");
    }
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $res = $conexion->query("SELECT * FROM calificaciones WHERE id=$id");
    $info = $res ? $res->fetch_assoc() : null;
    if ($conexion->query("DELETE FROM calificaciones WHERE id=$id")) {
        $mensaje = "Registro eliminado exitosamente.";
        if ($info) {
            registrarNotificacion("Se eliminó la calificación de {$info['nota']} en {$info['materia']}");
        }
    }
}

// Editar
$editar = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $res = $conexion->query("SELECT * FROM calificaciones WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $editar = $res->fetch_assoc();
    }
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
        <?php if ($notificaciones->num_rows > 0): ?>
            <span class="dot" style="
                background: red;
                border-radius: 50%;
                width: 10px;
                height: 10px;
                position: absolute;
                top: 0;
                right: 0;
            "></span>
        <?php endif; ?>
        <div id="notifBox" class="notif-dropdown">
            <?php while ($n = $notificaciones->fetch_assoc()): ?>
                <div class="notif-item"><?= $n['mensaje'] ?> <br><small><?= $n['fecha'] ?></small></div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<style>

body {

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
    <h2 style="color:#053c61; font-size:36px; text-align:center">Gestión de Calificaciones</h2>

    <!-- Mostrar mensaje -->
    <?php if ($mensaje): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; border-radius: 5px;">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return confirmarAccion(this);">
        <input type="hidden" name="id" value="<?= $editar['id'] ?? '' ?>">
        <label>Estudiante:</label>
        <select name="estudiante_id" required>
            <?php
            $estudiantes = $conexion->query("SELECT * FROM estudiantes");
            while ($row = $estudiantes->fetch_assoc()) {
                $selected = ($editar['estudiante_id'] ?? '') == $row['id'] ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>{$row['nombre_estudiante']}</option>";
            }
            ?>
        </select>
        <label>Materia:</label>
        <input type="text" name="materia" value="<?= $editar['materia'] ?? '' ?>" required>
        <label>Nota:</label>
        <input type="number" step="0.01" name="nota" value="<?= $editar['nota'] ?? '' ?>" required>
        <div class="btns">
            <button type="submit" name="crear">Crear</button>
            <button type="submit" name="actualizar">Actualizar</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Estudiante</th><th>Materia</th><th>Nota</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $resultado = $conexion->query("SELECT c.id, e.nombre_estudiante, c.materia, c.nota FROM calificaciones c JOIN estudiantes e ON c.estudiante_id = e.id");
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre_estudiante']}</td>
                    <td>{$row['materia']}</td>
                    <td>{$row['nota']}</td>
                    <td>
                        <a href='calificaciones.php?eliminar={$row['id']}' onclick='return confirmarEliminar();'>Eliminar</a>
                        <a href='calificaciones.php?editar={$row['id']}'>Editar</a>
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
    return confirm("¿Estás seguro de que deseas eliminar este registro?");
}

function confirmarAccion(form) {
    if (form.actualizar && form.actualizar.name === "actualizar" && form.actualizar.clicked) {
        return confirm("¿Estás seguro de que deseas actualizar este registro?");
    }
    return true;
}

// Detectar qué botón fue presionado
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


