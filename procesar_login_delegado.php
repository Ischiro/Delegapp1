<?php
session_start();

$servidor = "localhost";
$usuario_db = "root";
$contrasena_db = "";
$base_de_datos = "da";

$conexion = new mysqli($servidor, $usuario_db, $contrasena_db, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

if ($usuario && $contrasena) {
    $stmt = $conexion->prepare("SELECT nombre_usuario, contrasena, tipo FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();

        // Verifica la contraseña
        if (password_verify($contrasena, $fila['contrasena'])) {
            // Guardar datos en sesión
            $_SESSION['usuario'] = $fila['nombre_usuario'];
            $_SESSION['tipo'] = $fila['tipo'];

            // Redireccionar según tipo (opcional)
            if ($fila['tipo'] === 'delegado') {
                header("Location: portal_delegado.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
} else {
    echo "Por favor ingresa usuario y contraseña.";
}

$conexion->close();
?>
