<?php
session_start();
if (isset($_SESSION['usuario'])) {
    if ($_SESSION['tipo'] === 'ciudadano') {
        header("Location: portal_ciudadano.php");
    } else {
        header("Location: portal_delegado.php");
    }
    exit;
}
?>

<?php include("cabecera.php"); ?>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-container {
        max-width: 900px;
        margin: 80px auto;
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        display: flex;
        overflow: hidden;
    }

    .login-left {
        flex: 1;
        background-color: #1d3557;
        color: white;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-left h2 {
        font-size: 2.3rem;
        margin-bottom: 20px;
    }

    .login-left p {
        font-size: 1.1rem;
        line-height: 1.5;
    }

    .login-right {
        flex: 1.2;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-right h3 {
        text-align: center;
        margin-bottom: 25px;
        font-size: 2rem;
        color: #1d3557;
        font-weight: 700;
    }

    .login-form input,
    .login-form select {
        width: 100%;
        padding: 14px;
        border-radius: 25px;
        border: 1px solid #ccc;
        font-size: 1rem;
        text-align: center;
        margin-bottom: 20px;
        transition: 0.3s;
    }

    .login-form input:focus,
    .login-form select:focus {
        outline: none;
        border-color: #1d3557;
        box-shadow: 0 0 5px rgba(29, 53, 87, 0.5);
    }

    .login-form button,
    .login-form .gps-btn {
        width: 100%;
        padding: 14px;
        background-color: #1d3557;
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 1.1rem;
        font-weight: bold;
        transition: 0.3s;
        margin-bottom: 10px;
    }

    .login-form button:hover,
    .login-form .gps-btn:hover {
        background-color: #16324f;
    }

    .registro-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #1d3557;
        font-weight: bold;
        text-decoration: none;
    }

    .registro-link:hover {
        text-decoration: underline;
    }

    .gps-status {
        font-size: 0.95rem;
        color: #444;
        text-align: center;
        margin-bottom: 15px;
    }

    .error-msg {
        text-align: center;
        color: red;
        font-weight: bold;
        margin-bottom: 15px;
    }

    @media (max-width: 992px) {
        .login-container {
            flex-direction: column;
        }

        .login-left {
            text-align: center;
        }

        .login-right {
            padding: 40px;
        }
    }
</style>

<script>
    function verificarTipoUsuario() {
        const tipo = document.getElementById("tipoUsuario").value;
        const gpsBtn = document.getElementById("btnGPS");
        const ubicacionInput = document.getElementById("ubicacion");
        const statusGPS = document.getElementById("gpsStatus");

        if (tipo === "ciudadano") {
            gpsBtn.style.display = "block";
            statusGPS.style.display = "block";
        } else {
            gpsBtn.style.display = "none";
            statusGPS.style.display = "none";
            ubicacionInput.value = "";
        }
    }

    function activarGPS() {
        const statusGPS = document.getElementById("gpsStatus");
        const ubicacionInput = document.getElementById("ubicacion");
        const btnGPS = document.getElementById("btnGPS");

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                ubicacionInput.value = position.coords.latitude + "," + position.coords.longitude;
                statusGPS.innerText = "✅ GPS activado correctamente.";
                btnGPS.innerText = "GPS ACTIVADO";
                btnGPS.disabled = true;
            }, function () {
                statusGPS.innerText = "❌ No se pudo obtener la ubicación.";
            });
        } else {
            statusGPS.innerText = "⚠️ Tu navegador no soporta GPS.";
        }
    }
</script>

<div class="login-container">
    <div class="login-left">
        <h2>¡Bienvenido a DelegApp!</h2>
        <p>Inicia sesión para enviar reportes, revisar el estado de tus solicitudes o acceder al panel de administración como delegado.</p>
    </div>

    <div class="login-right">
        <h3>INICIAR SESIÓN</h3>

        <?php if (isset($_SESSION['error_login'])): ?>
            <div class="error-msg"><?php echo $_SESSION['error_login']; unset($_SESSION['error_login']); ?></div>
        <?php endif; ?>

        <form action="procesar_login.php" method="post" class="login-form">
            <input type="text" name="usuario" placeholder="Nombre de usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>

            <!-- Este select debe estar DENTRO del formulario -->
            <select name="tipo" id="tipoUsuario" onchange="verificarTipoUsuario()" required>
                <option value="" disabled selected>Selecciona tipo de usuario</option>
                <option value="ciudadano">Ciudadano</option>
                <option value="delegado">Delegado</option>
            </select>

            <input type="hidden" name="ubicacion" id="ubicacion">
            <div id="gpsStatus" class="gps-status" style="display: none;">📍 Esperando activación de GPS...</div>
            <button type="button" onclick="activarGPS()" id="btnGPS" class="gps-btn" style="display: none;">Activar GPS</button>

            <button type="submit">INGRESAR</button>
        </form>

        <a href="registro.php" class="registro-link">¿No tienes cuenta? Regístrate</a>
    </div>
</div>

<?php include("pie.php"); ?>
