<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
$usuario = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #053c61;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .dashboard-container h1 {
            font-size: 32px;
            color: #053c61;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
	
        }
        .dashboard-container h2 {
            color: #053c61;
            margin-bottom: 30px;
        }

        .dashboard-container a {
            display: inline-block;
            margin: 10px;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .dashboard-container a:hover {
            background-color: #0056b3;
        }

        .dashboard-container a.logout {
            background-color: #dc3545;
        }

        .dashboard-container a.logout:hover {
            background-color: #a71d2a;
        }


th{
Border-width:0;

}
td{
Border-width:0;
}

    </style>
</head>
<body>
    <div class="dashboard-container">

<img src="images/logo.png" width="150px" style="margin-bottom: -30px; margin-top: -30px;" />
        <h1>COLEGIO...</h1>
        <h2 style="color:#053c61">Bienvenido, <?= htmlspecialchars($usuario) ?></h2>




<div style="text-align: center;">
  <div style="display: inline-block; margin: 10px;">
    <img src="images/usuarios.png" width="100px" style="display: block; margin: 0 auto;" />
    <a href="usuarios.php" style="display: inline-block; margin-top: -10px;">
      Gestionar Usuarios
    </a>
  </div>

  <div style="display: inline-block; margin: 10px;">
    <img src="images/estudiantes.png" width="100px" style="display: block; margin: 0 auto;" />
    <a href="estudiantes.php" style="display: inline-block; margin-top: -10px;">
      Gestionar Estudiantes
    </a>
  </div>

  <div style="display: inline-block; margin: 10px;">
    <img src="images/calificaciones.png" width="100px" style="display: block; margin: 0 auto;" />
    <a href="calificaciones.php" style="display: inline-block; margin-top: -10px;">
      Gestionar Calificaciones
    </a>
  </div>

  <div style="display: inline-block; margin: 10px;">
    <img src="images/desempeño.png" width="100px" style="display: block; margin: 0 auto;" />
    <a href="desempeno.php" style="display: inline-block; margin-top: -10px;">
      Ver Desempeño
    </a>
  </div>

  <div style="margin-top: 20px;">
    <a href="logout.php" class="logout">
      Cerrar sesión
    </a>
  </div>
</div>




</body>


<footer style=" position: fixed; bottom: 0;  width: 100%;  background-color: #ffffff;   left: 0;
  color: black;  text-align: center;   padding: 5px 0;   font-family: 'Segoe UI', sans-serif;
  font-size: 14px; font-style: italic;"> -- Proyecto de Grado: Germán Correa & Omar Cortés --<br> Grupo: 202016907_82
</footer>
</html>
