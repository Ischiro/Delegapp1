<?php 
session_start(); 
include("cabecera.php"); 
?>

<script>
    window.onload = function () {
        actualizarFormulario();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    document.getElementById("ubicacion").value =
                        pos.coords.latitude + "," + pos.coords.longitude;
                },
                function () {
                    document.getElementById("ubicacion").value = "No disponible";
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            document.getElementById("ubicacion").value = "No compatible";
        }
    };

    function actualizarFormulario() {
        const tipo = document.querySelector('select[name="tipo"]').value;
        const titulo = document.getElementById("titulo-registro");
        const correo = document.getElementById("campo-correo");
        const ubicacion = document.getElementById("campo-ubicacion");

        if (tipo === "ciudadano") {
            titulo.textContent = "Registro Ciudadano";
            correo.style.display = "none";
            ubicacion.style.display = "block";
        } else if (tipo === "delegado") {
            titulo.textContent = "Registro Delegado";
            correo.style.display = "block";
            ubicacion.style.display = "none";
        } else {
            titulo.textContent = "REGÍSTRATE";
            correo.style.display = "none";
            ubicacion.style.display = "none";
        }
    }
</script>

<style>
    body {
        background-color: #f7f9fb;
        font-family: 'Segoe UI', sans-serif;
    }

    .contenedor-registro {
        max-width: 1100px;
        margin: 60px auto;
        background-color: #ffffff;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
    }

    .lado-izquierdo {
        flex: 1;
        background: linear-gradient(to bottom, #274472, #1b2a41);
        color: white;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .lado-izquierdo h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
    }

    .lado-izquierdo p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .lado-derecho {
        flex: 1.3;
        padding: 50px 60px;
    }

    .lado-derecho h3 {
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
        color: #1b2a41;
    }

    .formulario-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .formulario-grid input,
    .formulario-grid select {
        width: 100%;
        padding: 14px;
        border-radius: 25px;
        border: 1px solid #ccc;
        font-size: 1rem;
        transition: 0.3s;
        text-align: center;
    }

    .formulario-grid input:focus,
    .formulario-grid select:focus {
        outline: none;
        box-shadow: 0 0 0 3px #27447255;
        background-color: #fff;
    }

    .registro-boton {
        margin-top: 30px;
        padding: 14px;
        width: 100%;
        background-color: #1b2a41;
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 1.2rem;
        font-weight: bold;
        transition: 0.3s;
    }

    .registro-boton:hover {
        background-color: #274472;
    }

    .login-btn {
        display: block;
        margin-top: 15px;
        text-align: center;
        font-weight: bold;
        color: #274472;
        text-decoration: none;
    }

    .login-btn:hover {
        text-decoration: underline;
    }

    .selector-usuario {
        text-align: center;
        margin-bottom: 20px;
    }

    .selector-usuario select {
        padding: 10px 20px;
        border-radius: 20px;
        border: 1px solid #aaa;
        font-size: 1rem;
    }

    .mensaje {
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 15px;
        font-weight: bold;
        text-align: center;
    }

    .mensaje-error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .mensaje-exito {
        background-color: #d4edda;
        color: #155724;
    }

    @media (max-width: 992px) {
        .contenedor-registro {
            flex-direction: column;
        }

        .lado-izquierdo {
            text-align: center;
            padding: 30px;
        }

        .lado-derecho {
            padding: 30px;
        }

        .formulario-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="contenedor-registro">
    <!-- LADO IZQUIERDO -->
    <div class="lado-izquierdo">
        <h2>DelegApp</h2>
        <p>
            Mejora tu comunidad con reportes ciudadanos efectivos y seguimiento institucional. Únete como ciudadano o delegado. ¡Regístrate y sé parte del cambio!
        </p>
    </div>

    <!-- LADO DERECHO -->
    <div class="lado-derecho">
        <h3 id="titulo-registro">REGÍSTRATE</h3>

        <!-- Mostrar mensajes -->
        <?php if (isset($_SESSION['error_registro'])): ?>
            <div class="mensaje mensaje-error">
                <?php echo $_SESSION['error_registro']; unset($_SESSION['error_registro']); ?>
            </div>
        <?php elseif (isset($_SESSION['registro_exitoso'])): ?>
            <div class="mensaje mensaje-exito">
                <?php echo $_SESSION['registro_exitoso']; unset($_SESSION['registro_exitoso']); ?>
            </div>
        <?php endif; ?>

        <!-- FORMULARIO -->
        <form action="procesar_registro.php" method="post" autocomplete="off">
            <!-- IMPORTANTE: este select debe estar dentro del form -->
            <div class="selector-usuario">
                <label for="tipo">Selecciona tu tipo de usuario:</label><br>
                <select name="tipo" onchange="actualizarFormulario()" required>
                    <option value="" selected disabled>-- Selecciona una opción --</option>
                    <option value="delegado">Delegado</option>
                    <option value="ciudadano">Ciudadano</option>
                </select>
            </div>

            <div class="formulario-grid">
                <input type="text" name="usuario" placeholder="Nombre" required autocomplete="off">

                <div id="campo-correo" style="display: none;">
                    <input type="email" name="correo" placeholder="Correo electrónico" autocomplete="off">
                </div>

                <input type="password" name="contrasena" placeholder="Contraseña" required autocomplete="new-password">
                <input type="password" name="confirmar" placeholder="Confirmar contraseña" required autocomplete="new-password">

                <div id="campo-ubicacion" style="display: none;">
                    <input type="text" name="ubicacion" id="ubicacion" placeholder="Ubicación por GPS" readonly>
                </div>
            </div>

            <button type="submit" class="registro-boton">REGISTRARME</button>
            <a href="login.php" class="login-btn">Ya tengo cuenta, iniciar sesión</a>
        </form>
    </div>
</div>

<?php include("pie.php"); ?>
