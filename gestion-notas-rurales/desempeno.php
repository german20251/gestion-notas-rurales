<?php
include('db.php');

$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%%";
$materia = isset($_GET['materia']) ? "%" . $_GET['materia'] . "%" : "%%";

$sql = "
    SELECT 
        e.nombre_estudiante AS estudiante,
        c.materia,
        ROUND(AVG(c.nota), 2) AS promedio
    FROM calificaciones c
    JOIN estudiantes e ON c.estudiante_id = e.id
    WHERE e.nombre_estudiante LIKE ? AND c.materia LIKE ?
    GROUP BY e.nombre_estudiante, c.materia
    ORDER BY e.nombre_estudiante ASC
";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("ss", $search, $materia);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Desempeño Estudiantil</title>
    <style>
	
        body {
            font-family: Arial, sans-serif;
            background-color: #053c61;
            margin: 0;
            padding: 20px;
        }


        h2 {
            color: #053c61;
	    font-size: 36px;
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin: 5px;
        }

        input[type="submit"] {
            padding: 8px 16px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
        }

        .barra {
            height: 20px;
            border-radius: 10px;
        }

        .verde {
            background-color: green;
        }

        .amarillo {
            background-color: orange;
        }

        .rojo {
            background-color: red;
        }

        .volver-container {
            text-align: center;
            margin-top: 30px;
        }

        .btn-volver {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-volver:hover {
            background-color: #0056b3;
        }
.container {
            width: 100%;
            max-width: 85%;
            margin: 80px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            text-align: center;
    </style>
</head>
<body style="backround-color=#053c61">
<div class="container">
<img src="images/logo.png" width="150px" align="right" style="margin-bottom: -30px; margin-top: -30px; " />
    <h2>Desempeño Estudiantil</h2>

    <div class="search-container">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Buscar estudiante..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <input type="text" name="materia" placeholder="Filtrar por materia..." value="<?= htmlspecialchars($_GET['materia'] ?? '') ?>">
            <input type="submit" value="Filtrar">
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Materia</th>
                <th>Promedio</th>
                <th>Desempeño</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <?php
                    $color = "rojo";
                    if ($fila['promedio'] >= 4.0) {
                        $color = "verde";
                    } elseif ($fila['promedio'] >= 3.0) {
                        $color = "amarillo";
                    }
                    $porcentaje = ($fila['promedio'] / 5) * 100;
                ?>
                <tr>
                    <td><?= htmlspecialchars($fila['estudiante']) ?></td>
                    <td><?= htmlspecialchars($fila['materia']) ?></td>
                    <td><?= number_format($fila['promedio'], 2) ?></td>
                    <td>
                        <div style="background: #ddd; border-radius: 10px; overflow: hidden;">
                            <div class="barra <?= $color ?>" style="width: <?= $porcentaje ?>%"></div>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
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
</body>
<footer style=" position: fixed; bottom: 0;  width: 100%;  background-color: #ffffff;   left: 0;
  color: black;  text-align: center;   padding: 5px 0;   font-family: 'Segoe UI', sans-serif;
  font-size: 14px; font-style: italic;"> -- Proyecto de Grado: Germán Correa & Omar Cortés --<br> Grupo: 202016907_82
</footer>
</html>
