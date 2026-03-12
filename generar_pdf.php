<?php
require 'vendor/autoload.php'; // Carga Dompdf (asegúrate que vendor/ existe)

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

// Verifica si el usuario está logueado y es ciudadano
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'ciudadano') {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "da");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los reportes del usuario actual
$usuario = $_SESSION['usuario'];
$stmt = $conexion->prepare("SELECT * FROM reportes WHERE nombre = ? ORDER BY fecha DESC");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

// Generar HTML del PDF
$html = '
<h2 style="text-align:center; color:#1d3557;">📄 Reportes del Ciudadano</h2>
<table border="1" cellpadding="10" cellspacing="0" width="100%" style="font-family:sans-serif; font-size:12px; border-collapse:collapse;">
    <thead>
        <tr style="background-color:#1d3557; color:white;">
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Descripción</th>
            <th>Ubicación</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>';

while ($fila = $resultado->fetch_assoc()) {
    $html .= '<tr>
        <td>' . $fila['id'] . '</td>
        <td>' . htmlspecialchars($fila['nombre']) . '</td>
        <td>' . htmlspecialchars($fila['categoria']) . '</td>
        <td>' . htmlspecialchars($fila['descripcion']) . '</td>
        <td>' . htmlspecialchars($fila['ubicacion']) . '</td>
        <td>' . $fila['fecha'] . '</td>
        <td>' . $fila['estado'] . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Configurar Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Descargar el archivo
$filename = "reportes_" . $usuario . "_" . date("Ymd_His") . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
?>
