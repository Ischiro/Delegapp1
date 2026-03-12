<?php
$conexion = new mysqli("localhost", "root", "", "da");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$nombre      = isset($_POST['nombre']) ? $conexion->real_escape_string($_POST['nombre']) : '';
$seccion     = isset($_POST['seccion']) ? $conexion->real_escape_string($_POST['seccion']) : '';
$categoria   = isset($_POST['categoria']) ? $conexion->real_escape_string($_POST['categoria']) : '';
$descripcion = isset($_POST['descripcion']) ? $conexion->real_escape_string($_POST['descripcion']) : '';
$ubicacion   = isset($_POST['ubicacion']) ? $conexion->real_escape_string($_POST['ubicacion']) : '';

$nombreImagen = ""; // valor por defecto

// Validar si se subió una imagen
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
    $carpetaDestino = "imagenes/";
    if (!file_exists($carpetaDestino)) {
        mkdir($carpetaDestino, 0755, true); // crea la carpeta si no existe
    }

    $nombreTemporal = $_FILES["imagen"]["tmp_name"];
    $nombreImagen = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
    $rutaFinal = $carpetaDestino . $nombreImagen;

    move_uploaded_file($nombreTemporal, $rutaFinal);
}

if ($nombre && $seccion && $categoria && $descripcion && $ubicacion) {
    $sql = "INSERT INTO reporte (nombre, seccion, categoria, descripcion, ubicacion, imagen)
            VALUES ('$nombre', '$seccion', '$categoria', '$descripcion', '$ubicacion', '$nombreImagen')";

    if ($conexion->query($sql) === TRUE) {
        echo "<script>
                alert('✅ Reporte guardado correctamente con imagen.');
                window.location.href = 'crear_reporte.php';
              </script>";
    } else {
        echo "❌ Error al guardar: " . $conexion->error;
    }
} else {
    echo "<script>
            alert('❌ Todos los campos son obligatorios.');
            window.history.back();
          </script>";
}

$conexion->close();
?>
