<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "da");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = trim($_GET['buscar']);
    // Preparar consulta con búsqueda por ID o Nombre
    $stmt = $conexion->prepare("SELECT * FROM reportes WHERE id = ? OR nombre LIKE ? ORDER BY fecha DESC");
    $id_busqueda = is_numeric($busqueda) ? (int)$busqueda : 0;
    $like = "%" . $busqueda . "%";
    $stmt->bind_param("is", $id_busqueda, $like);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    // Mostrar todos los reportes si no hay búsqueda
    $resultado = $conexion->query("SELECT * FROM reportes ORDER BY fecha DESC");
}

?>
<?php include("cabecera.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Ciudadanos</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .contenedor {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #1d3557;
            margin-bottom: 25px;
        }
        form.busqueda {
            text-align: center;
            margin-bottom: 20px;
        }
        form.busqueda input[type="text"] {
            padding: 10px;
            width: 60%;
            max-width: 400px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        form.busqueda button {
            padding: 10px 20px;
            background-color: #1d3557;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            margin-left: 10px;
        }
        form.busqueda button:hover {
            background-color: #16324f;
        }
        .btn-limpiar {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ccc;
            color: #000;
            text-decoration: none;
            border-radius: 10px;
            margin-left: 10px;
            font-weight: bold;
        }
        .btn-limpiar:hover {
            background-color: #bbb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        th, td {
            border-bottom: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #1d3557;
            color: white;
        }
        .miniatura {
            max-width: 80px;
            max-height: 60px;
            border-radius: 5px;
        }
        .sin-imagen {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>📋 Reportes Ciudadanos</h2>

    <form class="busqueda" method="get">
        <input type="text" name="buscar" placeholder="Buscar por ID o Nombre..." value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit">🔍 Buscar</button>
        <?php if (!empty($busqueda)): ?>
            <a href="<?= basename(__FILE__) ?>" class="btn-limpiar">Mostrar todos</a>
        <?php endif; ?>
    </form>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Sección</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Ubicación</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['seccion']) ?></td>
                        <td><?= htmlspecialchars($fila['categoria']) ?></td>
                        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                        <td><?= htmlspecialchars($fila['ubicacion']) ?></td>
                        <td><?= $fila['fecha'] ?></td>
                        <td><?= htmlspecialchars($fila['estado']) ?></td>
                        <td>
                            <?php if (!empty($fila['imagen']) && file_exists("imagenes/" . $fila['imagen'])): ?>
                                <a href="imagenes/<?= $fila['imagen'] ?>" target="_blank">
                                    <img src="imagenes/<?= $fila['imagen'] ?>" alt="Imagen" class="miniatura">
                                </a>
                            <?php else: ?>
                                <span class="sin-imagen">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;color:#666;">No se encontraron reportes.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php
$conexion->close();
?>

<?php include("pie.php"); ?>