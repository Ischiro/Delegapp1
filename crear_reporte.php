<?php include("cabecera.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Reporte</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #eef2f5;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #1d3557;
            margin-bottom: 30px;
        }

        form {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        label {
            font-weight: bold;
            margin-top: 20px;
            display: block;
            color: #333;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 12px;
            border: 1px solid #ccc;
            font-size: 1rem;
            background: #fdfdfd;
        }

        input[type="file"] {
            padding: 6px;
        }

        button {
            margin-top: 25px;
            padding: 14px 20px;
            background-color: #1d3557;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #16324f;
        }

        .btn-ia {
            background-color: #457b9d;
            margin-top: 12px;
        }

        .btn-ia:hover {
            background-color: #336688;
        }
    </style>
</head>
<body>

<h2>📢 Crear Nuevo Reporte</h2>

<!-- ¡IMPORTANTE! enctype para permitir subir archivos -->
<form action="guardar_reporte.php" method="post" enctype="multipart/form-data">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" required>

    <label for="seccion">Sección:</label>
    <input type="text" name="seccion" id="seccion" required>

    <label for="categoria">Categoría del problema:</label>
    <select name="categoria" id="categoria" required onchange="cambiarPlaceholder()">
        <option value="">Selecciona una opción</option>
        <option value="Alumbrado público">Alumbrado público</option>
        <option value="Baches">Baches</option>
        <option value="Basura">Basura</option>
        <option value="Seguridad">Seguridad</option>
        <option value="Otro">Otro</option>
    </select>

    <label for="descripcion">Descripción del problema:</label>
    <textarea name="descripcion" id="descripcion" rows="5" required placeholder="Describe detalladamente el problema..."></textarea>
    <button type="button" class="btn-ia" onclick="ayudaIA()">✨ Ayudar con IA</button>

    <label for="ubicacion">Ubicación (calle, colonia):</label>
    <input type="text" name="ubicacion" id="ubicacion" required>

    <label for="imagen">Imagen del problema (opcional):</label>
    <input type="file" name="imagen" id="imagen" accept="image/*">

    <button type="submit">📤 Enviar Reporte</button>
</form>

<script>
function ayudaIA() {
    const categoria = document.getElementById("categoria").value;
    const descripcion = document.getElementById("descripcion");
    let texto = "";

    switch (categoria) {
        case "Alumbrado público":
            texto = "Se reporta una falla en el alumbrado público de la zona, lo que provoca oscuridad total durante la noche. Esto representa un riesgo para los peatones y vehículos.";
            break;
        case "Baches":
            texto = "Se ha identificado un bache considerable en la vía, que afecta la circulación de vehículos y puede causar accidentes. Se solicita pronta reparación.";
            break;
        case "Basura":
            texto = "Hay acumulación de residuos en la vía pública, generando malos olores y atrayendo fauna nociva. Urge recolección y limpieza.";
            break;
        case "Seguridad":
            texto = "Los vecinos reportan situaciones constantes de inseguridad en esta zona. Se requiere mayor presencia policiaca o acciones de vigilancia.";
            break;
        case "Otro":
            texto = "Por favor, proporciona una descripción específica del problema.";
            break;
        default:
            texto = "Selecciona una categoría válida.";
    }

    if (texto) {
        descripcion.value = texto;
    }
}

function cambiarPlaceholder() {
    const categoria = document.getElementById("categoria").value;
    const descripcion = document.getElementById("descripcion");

    if (categoria === "Otro") {
        descripcion.placeholder = "Especifica claramente el tipo de problema.";
    } else {
        descripcion.placeholder = "Puedes usar el botón ✨ 'Ayudar con IA' para generar una descripción.";
    }
}
</script>

</body>
</html>

<?php include("pie.php"); ?>
