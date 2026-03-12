<?php
session_start();

$codigo_correcto = "12345"; // Puedes cambiar esta clave maestra

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['codigo'] === $codigo_correcto) {
        $_SESSION['acceso_delegado'] = true;
        header("Location: login_delegado.php");
        exit;
    } else {
        $error = "❌ Código incorrecto. Inténtalo de nuevo.";
    }
}
?>

<?php include("cabecera.php"); ?>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', sans-serif;
    }

    .clave-container {
        max-width: 800px;
        margin: 100px auto;
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        display: flex;
        overflow: hidden;
    }

    .clave-left {
        flex: 1;
        background-color: #1d3557;
        color: white;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .clave-left h2 {
        font-size: 2.3rem;
        margin-bottom: 20px;
    }

    .clave-left p {
        font-size: 1.1rem;
        line-height: 1.5;
    }

    .clave-right {
        flex: 1.2;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .clave-right h3 {
        text-align: center;
        margin-bottom: 25px;
        font-size: 2rem;
        color: #1d3557;
        font-weight: 700;
    }

    .clave-form input {
        width: 100%;
        padding: 14px;
        border-radius: 25px;
        border: 1px solid #ccc;
        font-size: 1rem;
        text-align: center;
        margin-bottom: 20px;
        transition: 0.3s;
    }

    .clave-form input:focus {
        outline: none;
        border-color: #1d3557;
        box-shadow: 0 0 5px rgba(29, 53, 87, 0.5);
    }

    .clave-form button {
        width: 100%;
        padding: 14px;
        background-color: #1d3557;
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 1.2rem;
        font-weight: bold;
        transition: 0.3s;
    }

    .clave-form button:hover {
        background-color: #16324f;
    }

    .clave-alert {
        margin-top: 20px;
        color: #c62828;
        font-weight: bold;
        text-align: center;
    }

    @media (max-width: 992px) {
        .clave-container {
            flex-direction: column;
        }

        .clave-left {
            text-align: center;
        }

        .clave-right {
            padding: 40px;
        }
    }
</style>

<div class="clave-container">
    <!-- Izquierda -->
    <div class="clave-left">
        <h2>Acceso Delegado</h2>
        <p>Solo personal autorizado puede ingresar a esta sección. Ingresa tu código secreto para continuar hacia el portal de delegados.</p>
    </div>

    <!-- Derecha -->
    <div class="clave-right">
        <h3>VALIDAR CÓDIGO</h3>
        <form method="POST" class="clave-form">
            <input type="password" name="codigo" placeholder="Código de acceso" required autofocus />
            <button type="submit">INGRESAR</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="clave-alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>
</div>

<?php include("pie.php"); ?>
