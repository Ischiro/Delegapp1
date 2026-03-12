<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'ciudadano') {
    header("Location: login.php");
    exit;
}

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$conexion = new mysqli("localhost", "root", "", "da");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = "";
$archivo_pdf = "";

function generarDescripcionIA($texto, $categoria, $ubicacion) {
    if (strlen(trim($texto)) < 15) {
        switch ($categoria) {
            case "Alumbrado público":
                return "Se reporta una falla en el alumbrado público en la zona $ubicacion. La falta de iluminación representa un riesgo para la seguridad de los vecinos.";
            case "Baches":
                return "En $ubicacion se localiza un bache que representa un riesgo para vehículos y peatones. Se solicita su pronta reparación.";
            case "Basura":
                return "Se ha detectado acumulación de basura en $ubicacion, lo cual genera malos olores y posibles focos de infección.";
            case "Seguridad":
                return "En $ubicacion se reporta una situación de inseguridad. Se solicita patrullaje frecuente para garantizar la tranquilidad de los ciudadanos.";
            default:
                return "Reporte en $ubicacion. Se requiere atención inmediata.";
        }
    } else {
        return $texto . " Este reporte fue complementado automáticamente por el sistema para mejorar su claridad.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_SESSION['usuario'];
    $categoria = $_POST['categoria'];
    $ubicacion = $_POST['ubicacion'];
    $descripcion_usuario = $_POST['descripcion'];
    $fecha = date("Y-m-d H:i:s");

    $descripcion = generarDescripcionIA($descripcion_usuario, $categoria, $ubicacion);

    $nombre_imagen = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombre_original = basename($_FILES['imagen']['name']);
        $nombre_imagen = time() . "_" . $nombre_original;
        move_uploaded_file($_FILES['imagen']['tmp_name'], "imagenes/" . $nombre_imagen);
    }

    $stmt = $conexion->prepare("INSERT INTO reportes (nombre, categoria, descripcion, ubicacion, fecha, estado, imagen) VALUES (?, ?, ?, ?, ?, 'Enviado', ?)");
    $stmt->bind_param("ssssss", $nombre_usuario, $categoria, $descripcion, $ubicacion, $fecha, $nombre_imagen);
    $stmt->execute();

    $html = "
        <h1 style='color:#1d3557;'>Reporte Ciudadano</h1>
        <p><strong>Nombre:</strong> $nombre_usuario</p>
        <p><strong>Categoría:</strong> $categoria</p>
        <p><strong>Descripción:</strong> $descripcion</p>
        <p><strong>Ubicación:</strong> $ubicacion</p>
        <p><strong>Fecha:</strong> $fecha</p>
    ";
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4');
    $dompdf->render();
    $archivo_pdf = "reportes_pdf/" . $nombre_usuario . "_" . time() . ".pdf";
    file_put_contents($archivo_pdf, $dompdf->output());

    $mensaje = "✅ Reporte enviado correctamente.";
}

$resultado = $conexion->query("SELECT * FROM reportes WHERE nombre = '{$_SESSION['usuario']}' ORDER BY fecha DESC");
include("cabecera.php");
?>

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
        border-radius: 25px;
        padding: 30px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        color: #1d3557;
        margin-bottom: 20px;
    }

    form input, form textarea, form select {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 12px;
        border: 1px solid #ccc;
        font-size: 1rem;
    }

    .btn {
        width: 100%;
        padding: 12px;
        font-weight: bold;
        font-size: 1rem;
        color: white;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-enviar {
        background-color: #1d3557;
    }

    .btn-enviar:hover {
        background-color: #16324f;
    }

    .btn-ia {
        background-color: #457b9d;
        margin-top: -10px;
        margin-bottom: 20px;
    }

    .btn-ia:hover {
        background-color: #356088;
    }

    .mensaje {
        color: green;
        text-align: center;
        font-weight: bold;
    }

    .descargar a {
        display: inline-block;
        background-color: #1d3557;
        color: white;
        padding: 8px 16px;
        border-radius: 10px;
        text-decoration: none;
        margin-top: 10px;
    }

    table {
        width: 100%;
        margin-top: 30px;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #1d3557;
        color: white;
    }

    .img-ver {
        color: #1d3557;
        font-weight: bold;
        text-decoration: none;
    }

    .img-ver:hover {
        text-decoration: underline;
    }
</style>

<div class="contenedor">
    <h2>📌 Enviar Reporte Ciudadano</h2>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="categoria">Categoría del problema:</label>
        <select name="categoria" id="categoria" required>
            <option value="">Selecciona una opción</option>
            <option value="Alumbrado público">Alumbrado público</option>
            <option value="Baches">Baches</option>
            <option value="Basura">Basura</option>
            <option value="Seguridad">Seguridad</option>
            <option value="Otro">Otro</option>
        </select>

        <label for="descripcion">Descripción del problema:</label>
        <textarea name="descripcion" id="descripcion" rows="4" placeholder="Ej: Aquí en el Oxxo de la colonia Centro hay un problema..." required></textarea>
        <button type="button" class="btn btn-ia" onclick="complementarDescripcionIA()">✨ Complementar con IA</button>

        <label for="ubicacion">Ubicación (calle, colonia):</label>
        <input type="text" name="ubicacion" id="ubicacion" placeholder="Ej: Calle Juárez, colonia Centro" required>

        <input type="file" name="imagen" accept="image/*">
        <button type="submit" class="btn btn-enviar">Enviar Reporte</button>
    </form>

    <?php if ($archivo_pdf): ?>
        <div class="descargar">
            <a href="<?php echo $archivo_pdf; ?>" download>📄 Descargar PDF del reporte</a>
        </div>
        
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Categoría</th>
            <th>Descripción</th>
            <th>Ubicación</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Imagen</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo htmlspecialchars($fila['categoria']); ?></td>
            <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
            <td><?php echo htmlspecialchars($fila['ubicacion']); ?></td>
            <td><?php echo $fila['fecha']; ?></td>
            <td><?php echo $fila['estado']; ?></td>
            <td>
                <?php if (!empty($fila['imagen'])): ?>
                    <a class="img-ver" href="imagenes/<?php echo $fila['imagen']; ?>" target="_blank">Ver</a>
                <?php else: ?>
                    Sin imagen
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<div style="margin-top: 30px; display: flex; flex-direction: column; align-items: center; gap: 15px;">
    <a href="crear_reporte.php" class="btn btn-primary">Crear Reporte</a>
    <a href="ver_reportes.php" class="btn btn-success">Ver reportes</a>
</div>
<script>
function complementarDescripcionIA() {
    const categoria = document.getElementById("categoria").value;
    const ubicacion = document.getElementById("ubicacion").value;
    let descripcion = document.getElementById("descripcion").value.trim();

    if (!descripcion || descripcion.length < 10) {
        switch (categoria) {
            case "Alumbrado público":
                descripcion = "En " + ubicacion + ", hay una falla de alumbrado público que afecta la seguridad en la zona.";
                break;
            case "Baches":
                descripcion = "Se detectó un bache peligroso en " + ubicacion + ", afecta el tránsito vehicular y peatonal.";
                break;
            case "Basura":
                descripcion = "Hay acumulación de basura en " + ubicacion + ", lo que genera malos olores y contaminación visual.";
                break;
            case "Seguridad":
                descripcion = "Vecinos reportan incidentes de inseguridad en " + ubicacion + ". Se solicita mayor vigilancia.";
                break;
            default:
                descripcion = "Reporte automático generado para " + ubicacion + ".";
        }
    } else {
        descripcion += " Este texto fue complementado automáticamente para mayor claridad.";
    }

    document.getElementById("descripcion").value = descripcion;
}
</script>


<a type="button">a</a>
<?php include("pie.php"); ?>
<?php $conexion->close(); ?>
