<?php
session_start();
// Si ya está logueado, redirige siempre al ciudadano
if (isset($_SESSION['usuario'])) {
    header("Location: ciudadano.php");  // ↑ MODIFICADO: sólo ciudadano
    exit;
}
?>

<?php include("cabecera.php"); ?>

<style>
    body {
        background-color: #f4f6fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-container {
        max-width: 1000px;
        margin: 80px auto;
        background-color: #fff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
    }

    .login-left {
        flex: 1;
        background: linear-gradient(to bottom, #274472, #1b2a41);
        color: white;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-left h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
    }

    .login-left p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .login-right {
        flex: 1.2;
        padding: 50px 60px;
    }

    .login-right h3 {
        text-align: center;
        font-weight: bold;
        margin-bottom: 30px;
        color: #1b2a41;
    }

    .login-form input {
        width: 100%;
        padding: 14px;
        border-radius: 25px;
        border: 1px solid #ccc;
        font-size: 1rem;
        margin-bottom: 20px;
        text-align: center;
        transition: 0.3s;
    }

    .login-form input:focus {
        outline: none;
        box-shadow: 0 0 0 3px #27447250;
        background-color: #fff;
    }

    .login-form button {
        width: 100%;
        padding: 14px;
        background-color: #1b2a41;
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 1.2rem;
        font-weight: bold;
        transition: 0.3s;
    }

    .login-form button:hover {
        background-color: #274472;
    }

    .registro-link {
        display: block;
        margin-top: 20px;
        text-align: center;
        font-weight: bold;
        color: #274472;
        text-decoration: none;
    }

    .registro-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 992px) {
        .login-container {
            flex-direction: column;
        }

        .login-left {
            text-align: center;
        }

        .login-right {
            padding: 30px;
        }
    }
</style>

<div class="login-container">
    <!-- LADO IZQUIERDO -->
    <div class="login-left">
        <h2>DelegApp</h2>
        <p>Bienvenido ciudadano. Inicia sesión para registrar reportes, seguir el estado de tus incidencias y colaborar activamente con tu comunidad.</p>
    </div>

    <!-- LADO DERECHO -->
    <div class="login-right">
        <h3>INICIAR SESIÓN CIUDADANO</h3>
        <form action="procesar_login.php" method="post" class="login-form" autocomplete="off">
            <input type="text" name="usuario" placeholder="Nombre de usuario" required autocomplete="off">
            <input type="password" name="contrasena" placeholder="Contraseña" required autocomplete="new-password">
            <!-- Campo oculto para forzar tipo ciudadano -->
            <input type="hidden" name="tipo" value="ciudadano"> <!-- ↑ MODIFICADO -->
            <button type="submit">INGRESAR</button>
        </form>
        <a href="registro.php" class="registro-link">¿No tienes cuenta? Regístrate aquí</a>
    </div>
</div>

<?php include("pie.php"); ?>
