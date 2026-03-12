<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "da");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$id_usuario = intval($_SESSION['user_id']);
$resultado = $mysqli->query("SELECT * FROM notificaciones WHERE id_usuario = $id_usuario ORDER BY fecha DESC");
?>

<?php include("cabecera.php"); ?>

<div class="container mt-4">
    <h2>🔔 Mis Notificaciones</h2>
    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <ul class="list-group mt-3">
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <li class="list-group-item <?= $fila['leido'] ? '' : 'font-weight-bold' ?>">
                    <?= htmlspecialchars($fila['mensaje']) ?>
                    <br><small class="text-muted"><?= $fila['fecha'] ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
        <?php
            // Marcar como leídas
            $mysqli->query("UPDATE notificaciones SET leido = 1 WHERE id_usuario = $id_usuario");
        ?>
    <?php else: ?>
        <p class="mt-3 text-muted">No tienes notificaciones por ahora.</p>
    <?php endif; ?>
</div>

<?php include("pie.php"); ?>
<?php $mysqli->close(); ?>
