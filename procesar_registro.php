<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "da");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$usuario = trim($_POST['usuario'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';
$tipo = $_POST['tipo'] ?? '';

// Validaciones básicas
if (empty($usuario) || empty($contrasena) || empty($confirmar) || empty($tipo)) {
    $_SESSION['error_registro'] = "❌ Todos los campos son obligatorios.";
    header("Location: registro.php");
    exit;
}

if ($contrasena !== $confirmar) {
    $_SESSION['error_registro'] = "❌ Las contraseñas no coinciden.";
    header("Location: registro.php");
    exit;
}

// Verificar si el usuario ya existe
$verificar = $conexion->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ?");
$verificar->bind_param("s", $usuario);
$verificar->execute();
$resultado = $verificar->get_result();

if ($resultado->num_rows > 0) {
    $_SESSION['error_registro'] = "❌ El nombre de usuario ya está registrado.";
    header("Location: registro.php");
    exit;
}
$verificar->close();

// Encriptar y registrar
$hash = password_hash($contrasena, PASSWORD_DEFAULT);

$sentencia = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, tipo) VALUES (?, ?, ?)");
$sentencia->bind_param("sss", $usuario, $hash, $tipo);

if ($sentencia->execute()) {
    $_SESSION['registro_exitoso'] = "✅ Registro exitoso. Ahora puedes iniciar sesión.";
    header("Location: registro.php");
    exit;
} else {
    $_SESSION['error_registro'] = "❌ Error al registrar. Intenta más tarde.";
    header("Location: registro.php");
    exit;
}

$sentencia->close();
$conexion->close();
