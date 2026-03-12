<?php
session_start();

$servidor      = "localhost";
$usuario_db    = "root";
$contrasena_db = "";
$base_de_datos = "da";

// Conexión
$conexion = new mysqli($servidor, $usuario_db, $contrasena_db, $base_de_datos);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibe datos del formulario
$usuario          = trim($_POST['usuario'] ?? '');
$contrasena       = $_POST['contrasena'] ?? '';
$tipo_seleccionado = $_POST['tipo'] ?? '';

if ($usuario !== '' && $contrasena !== '' && $tipo_seleccionado !== '') {
    // Trae el id, nombre, hash de contraseña, tipo y correo
    $stmt = $conexion->prepare("
        SELECT id, nombre_usuario, contrasena, tipo, correo
        FROM usuarios
        WHERE nombre_usuario = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();

        if (password_verify($contrasena, $fila['contrasena'])) {
            // Verifica que el tipo coincida
            if ($fila['tipo'] === $tipo_seleccionado) {
                // Guarda en sesión
                $_SESSION['user_id']  = $fila['id'];
                $_SESSION['usuario']  = $fila['nombre_usuario'];
                $_SESSION['tipo']     = $fila['tipo'];
                if ($fila['tipo'] === 'delegado') {
                    $_SESSION['email'] = $fila['correo'];
                }

                // Redirige según tipo
                if ($fila['tipo'] === 'ciudadano') {
                    header("Location: portal_ciudadano.php");
                } else { // delegado
                    header("Location: portal_delegado.php");
                }
                exit;
            } else {
                $_SESSION['error_login'] = "⚠️ El usuario no pertenece al tipo seleccionado.";
            }
        } else {
            $_SESSION['error_login'] = "❌ Contraseña incorrecta.";
        }
    } else {
        $_SESSION['error_login'] = "❌ Usuario no encontrado.";
    }

    $stmt->close();
} else {
    $_SESSION['error_login'] = "⚠️ Todos los campos son obligatorios.";
}

$conexion->close();
header("Location: login.php");
exit;
?>
