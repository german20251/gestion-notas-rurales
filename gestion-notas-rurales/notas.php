<?php
include("db.php");
$resultado = $conexion->query("SELECT * FROM estudiantes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notas de Estudiantes</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #053c61;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #053c61;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .volver {
            margin-top: 20px;
            text-align: center;
        }

        .volver a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .volver a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Notas de Estudiantes</h2>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Materia</th>
            <th>Nota</th>
        </tr>
        <?php while($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($fila['nombre_estudiante']) ?></td>
                <td>Matemáticas</td>
                <td>4.5</td> <!-- Esto puede cambiarse dinámicamente si lo deseas -->
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="volver">
        <a href="dashboard.php">Volver al panel</a>
    </div>
</div>

</body>
</html>

