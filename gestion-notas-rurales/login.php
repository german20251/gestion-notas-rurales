<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    $sql = "SELECT * FROM usuarios WHERE nombre_usuario='$usuario' AND contrasena='$contrasena'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 1) {
        $_SESSION["usuario"] = $usuario;
        header("Location: dashboard.php");
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        body {
            background-color: #053c61;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            text-align: center;
        }

        .container h1 {
            font-size: 32px;
            color: #053c61;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .container h2 {
            margin-bottom: 20px;
            color: #053c61;
            font-weight: bold;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .login-form label {
            text-align: left;
            font-weight: 500;
            color: #333;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .login-form input[type="submit"] {
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
	<img src="images/logo.png" width="150px" style="margin-bottom: -30px; margin-top: -30px;" />
        <h1>COLEGIO ...</h1>
        <h2>Iniciar Sesión</h2>

        <?php if(isset($error)): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label for="usuario">Usuario</label> 
	    <input type="text" name="usuario" id="usuario" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" required>

            <input type="submit" value="Ingresar">
        </form>
    </div>
<footer style=" position: fixed; bottom: 0;  width: 100%;  background-color: #ffffff;
  color: black;  text-align: center;   padding: 5px 0;   font-family: 'Segoe UI', sans-serif;
  font-size: 14px; font-style: italic;"> -- Proyecto de Grado: Germán Correa & Omar Cortés --<br> Grupo: 202016907_82
</footer>
</body>

</html>
