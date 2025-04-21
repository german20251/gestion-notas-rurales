<?php
include("db.php");
include("notificar.php");
$mensaje = "";

// Crear usuario
if (isset($_POST['crear'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];
    if ($conexion->query("INSERT INTO usuarios (nombre_usuario, contrasena, rol) VALUES ('$usuario', '$contrasena', '$rol')")) {
        $mensaje = "Usuario creado exitosamente.";
        registrarNotificacion("Se creo el usuario: $usuario");
    }
}

// Actualizar usuario
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];
    if ($conexion->query("UPDATE usuarios SET nombre_usuario='$usuario', contrasena='$contrasena', rol='$rol' WHERE id=$id")) {
        $mensaje = "Usuario actualizado exitosamente.";
        registrarNotificacion("Se actualizo el usuario con ID $id a nombre: $usuario");
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $res = $conexion->query("SELECT nombre_usuario FROM usuarios WHERE id=$id");
    $usuarioNombre = $res ? $res->fetch_assoc()['nombre_usuario'] : '';
    if ($conexion->query("DELETE FROM usuarios WHERE id=$id")) {
        $mensaje = "Usuario eliminado exitosamente.";
        registrarNotificacion("Se elimino el usuario: $usuarioNombre");
    }
}

// Editar usuario
$editar = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $res = $conexion->query("SELECT * FROM usuarios WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $editar = $res->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestion de Usuarios</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
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
</head>
<body style="background:#053c61">

<!-- Campana de notificaciones -->
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

<!-- Contenido principal -->
<div class="container">
<img src="images/logo.png" width="150px" align="right" style="margin-bottom: -30px; margin-top: -30px; " />   
 <h2 style="color:#053c61; font-size:36px">Gestion de Usuarios</h2>

    <?php if ($mensaje): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; border-radius: 5px;">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return confirmarAccion(this);">
        <input type="hidden" name="id" value="<?= $editar['id'] ?? '' ?>">
        <label>Usuario:</label>
        <input type="text" name="usuario" value="<?= $editar['nombre_usuario'] ?? '' ?>" required>
        <label>Contrasena:</label>
        <input type="password" name="contrasena" value="<?= $editar['contrasena'] ?? '' ?>" required>
        <label>Rol:</label>
        <select name="rol" required>
            <option value="docente" <?= ($editar['rol'] ?? '') == 'docente' ? 'selected' : '' ?>>Docente</option>
            <option value="directivo" <?= ($editar['rol'] ?? '') == 'directivo' ? 'selected' : '' ?>>Directivo</option>
        </select>
        <div class="btns">
            <button type="submit" name="crear">Crear</button>
            <button type="submit" name="actualizar">Actualizar</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Usuario</th><th>Rol</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $resultado = $conexion->query("SELECT * FROM usuarios");
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre_usuario']}</td>
                    <td>{$row['rol']}</td>
                    <td>
                        <a href='usuarios.php?editar={$row['id']}'>Editar</a>
                        <a href='usuarios.php?eliminar={$row['id']}' onclick='return confirmarEliminar();'>Eliminar</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: center;">
        <a href="dashboard.php">
            <button style="padding: 10px 20px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Volver
            </button>
        </a>
    </div>
</div>

<script>
function confirmarEliminar() {
    return confirm("?Estas seguro de que deseas eliminar este usuario?");
}

function confirmarAccion(form) {
    if (form.actualizar && form.actualizar.name === "actualizar" && form.actualizar.clicked) {
        return confirm("?Estas seguro de que deseas actualizar este usuario?");
    }
    return true;
}

// Detectar qu¨¦ bot¨®n fue presionado
document.querySelectorAll("form button").forEach(btn => {
    btn.addEventListener("click", function () {
        if (this.name === "actualizar") {
            this.form.actualizar.clicked = true;
        }
    });
});
</script>
</body>
<footer style=" position: fixed; bottom: 0;  width: 100%;  background-color: #ffffff;   left: 0;
  color: black;  text-align: center;   padding: 5px 0;   font-family: 'Segoe UI', sans-serif;
  font-size: 14px; font-style: italic;"> -- Proyecto de Grado: German Correa & Omar Cortes --<br> Grupo: 202016907_82
</footer>
</html>
