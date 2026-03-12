<?php
session_start();

// Validar sesión y tipo de usuario
if (!isset($_SESSION['usuario'])) {
    header("Location: /delegapp/login.php");
    exit;
}
if (($_SESSION['tipo'] ?? '') !== 'delegado') {
    header("Location: /delegapp/portal_ciudadano.php");
    exit;
}

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "da");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Procesar actualización de estado y generar notificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reporte'], $_POST['nuevo_estado'])) {
    $id = intval($_POST['id_reporte']);
    $nuevo_estado = $mysqli->real_escape_string($_POST['nuevo_estado']);

    // 1. Actualizar estado
    $stmt = $mysqli->prepare("UPDATE reportes SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id);
    $stmt->execute();
    $stmt->close();

    // 2. Obtener usuario del reporte
    $res = $mysqli->query("SELECT id_usuario FROM reportes WHERE id = $id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $id_usuario = $res->fetch_assoc()['id_usuario'];

        // 3. Crear mensaje
        $mensaje = "📢 Tu reporte #$id ha sido actualizado a '$nuevo_estado'.";

        // 4. Insertar notificación
        $stmt2 = $mysqli->prepare("INSERT INTO notificaciones (id_usuario, mensaje) VALUES (?, ?)");
        $stmt2->bind_param("is", $id_usuario, $mensaje);
        $stmt2->execute();
        $stmt2->close();
    }

    $_SESSION['mensaje_estado'] = "✅ Estado del reporte #$id actualizado a '$nuevo_estado'";
    header("Location: /delegapp/portal_delegado.php");
    exit;
}

// Obtener reportes
$resultado = $mysqli->query("SELECT id, nombre, categoria, descripcion, ubicacion, fecha, imagen, estado FROM reportes ORDER BY fecha DESC");

require_once "cabecera.php";
?>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
    }

    .cuadro-delegado {
        max-width: 1100px;
        margin: auto;
        background-color: #ffffff;
        border-radius: 25px;
        padding: 40px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .cuadro-delegado h2 {
        text-align: center;
        color: #1d3557;
        margin-bottom: 30px;
        font-size: 2rem;
    }

    .mensaje-exito {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 10px;
        padding: 12px 20px;
        margin-bottom: 25px;
        font-weight: bold;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    th, td {
        padding: 14px 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #1d3557;
        color: white;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .estado-select {
        padding: 6px 10px;
        border-radius: 12px;
        border: 1px solid #ccc;
        font-size: 0.9rem;
    }

    .boton-guardar {
        background-color: #1d3557;
        color: white;
        border: none;
        padding: 6px 12px;
        font-size: 0.9rem;
        font-weight: bold;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.3s;
        margin-left: 6px;
    }

    .boton-guardar:hover {
        background-color: #16324f;
    }

    .miniatura {
        width: 100px;
        border-radius: 8px;
        box-shadow: 0 0 3px rgba(0,0,0,0.2);
    }

    .ver-imagen {
        text-decoration: none;
        color: #1d3557;
        font-weight: bold;
    }

    .sin-imagen {
        color: #999;
        font-style: italic;
    }

    @media screen and (max-width: 768px) {
        table, th, td {
            font-size: 0.85rem;
        }
        .cuadro-delegado {
            padding: 20px;
        }
    }
</style>

<main>
    <div class="cuadro-delegado">
        <h2>📋 Panel de Reportes Ciudadanos</h2>

        <?php if (isset($_SESSION['mensaje_estado'])): ?>
            <div class="mensaje-exito">
                <?= htmlspecialchars($_SESSION['mensaje_estado']) ?>
                <?php unset($_SESSION['mensaje_estado']); ?>
            </div>
        <?php endif; ?>

        <?php if ($resultado && $resultado->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Ubicación</th>
                    <th>Fecha</th>
                    <th>Imagen</th>
                    <th>Estado</th>
                    <th>Actualizar</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                    <td><?= htmlspecialchars($fila['categoria']) ?></td>
                    <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                    <td><?= htmlspecialchars($fila['ubicacion']) ?></td>
                    <td><?= $fila['fecha'] ?></td>
                    <td>
                        <?php if (!empty($fila['imagen']) && file_exists("imagenes/" . $fila['imagen'])): ?>
                            <a class="ver-imagen" href="imagenes/<?= $fila['imagen'] ?>" target="_blank">
                                <img src="imagenes/<?= $fila['imagen'] ?>" alt="Imagen" class="miniatura">
                            </a>
                        <?php else: ?>
                            <span class="sin-imagen">Sin imagen</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($fila['estado'] ?: 'Enviado') ?></strong></td>
                    <td>
                        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" style="display:flex;align-items:center;justify-content:center;">
                            <input type="hidden" name="id_reporte" value="<?= $fila['id'] ?>">
                            <select name="nuevo_estado" class="estado-select">
                                <option value="Recibido" <?= $fila['estado'] == 'Recibido' ? 'selected' : '' ?>>Recibido</option>
                                <option value="Aceptado" <?= $fila['estado'] == 'Aceptado' ? 'selected' : '' ?>>Aceptado</option>
                                <option value="Rechazado" <?= $fila['estado'] == 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                            </select>
                            <button type="submit" class="boton-guardar">Guardar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="text-align:center;color:gray;">No hay reportes por ahora.</p>
        <?php endif; ?>
    </div>
</main>

<?php
require_once "pie.php";
$mysqli->close();
?>
