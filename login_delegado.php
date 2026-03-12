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

    .container-login {
        max-width: 1000px;
        margin: 80px auto;
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
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
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-right h3 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2rem;
        color: #1d3557;
        font-weight: 700;
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
        border-color: #1d3557;
        box-shadow: 0 0 0 3px rgba(29, 53, 87, 0.2);
    }

    .login-form button {
        width: 100%;
        padding: 14px;
        background-color: #1d3557;
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 1.2rem;
        font-weight: bold;
        transition: background 0.3s;
    }

    .login-form button:hover {
        background-color: #16324f;
    }

    .registro-link {
        display: block;
        margin-top: 20px;
        text-align: center;
        font-weight: bold;
        color: #1d3557;
        text-decoration: none;
    }

    .registro-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 992px) {
        .container-login {
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

<div class="container-login">
    <!-- Izquierda -->
    <div class="login-left">
        <h2>Delegado</h2>
        <p>Bienvenido a DelegApp. Accede para gestionar reportes ciudadanos, responder solicitudes y dar seguimiento a los problemas de tu comunidad.</p>
    </div>

    <!-- Derecha -->
    <div class="login-right">
        <h3>INICIAR SESIÓN - DELEGADO</h3>
        <form action="procesar_login_delegado.php" method="post" class="login-form" autocomplete="off">
            <input type="text" name="usuario" placeholder="Nombre de usuario" required autocomplete="off">
            <input type="password" name="contrasena" placeholder="Contraseña" required autocomplete="new-password">
            <button type="submit">INGRESAR</button>
        </form>
        <a href="registro.php" class="registro-link">¿No tienes cuenta? Regístrate</a>
    </div>
</div>

<?php include("pie.php"); ?>
