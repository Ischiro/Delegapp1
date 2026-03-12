<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Recoge los datos del formulario
$id = intval($_SESSION['user_id']);
$nombre = trim($_POST['nombre']);

// Determina si es delegado
$isDeleg = ($_SESSION['tipo'] ?? '') === 'delegado';
$email = $isDeleg && isset($_POST['email']) ? trim($_POST['email']) : null;

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "da");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Actualiza según el rol
if ($isDeleg) {
    $stmt = $mysqli->prepare("UPDATE usuarios SET nombre_usuario = ?, correo = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nombre, $email, $id);
} else {
    $stmt = $mysqli->prepare("UPDATE usuarios SET nombre_usuario = ? WHERE id = ?");
    $stmt->bind_param("si", $nombre, $id);
}

// Ejecutar y actualizar sesión
if ($stmt->execute()) {
    $_SESSION['usuario'] = $nombre;
    if ($isDeleg) {
        $_SESSION['email'] = $email;
    }
    $msg = "✅ Perfil actualizado correctamente.";
} else {
    $msg = "❌ Error al actualizar: " . $mysqli->error;
}

$stmt->close();
$mysqli->close();

// Redirige con mensaje
$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $back?msg=" . urlencode($msg));
exit;
