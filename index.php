<?php include("cabecera.php"); ?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        width: 100%;
        background-color: #edf4f2; /* Verde claro: relajante, institucional */
        font-family: 'Segoe UI', sans-serif;
        color: #333;
    }

    .contenedor-delegapp {
        background-color: #edf4f2;
        width: 100%;
        padding: 20px 0 40px 0;
    }

    .contenido-flex {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 40px;
        max-width: 1200px;
        margin: auto;
    }

    .texto-delegapp {
        flex: 1 1 380px;
        padding-top: 25px;
        text-align: left;
        font-family: 'Nunito', sans-serif;
        font-weight: 600;
    }

    .texto-delegapp h1 {
        font-size: 2.8em;
        margin-bottom: 10px;
        color: #1d3557; /* Azul oscuro: institucional y confiable */
    }

    .texto-delegapp .lead {
        font-size: 1.3em;
        color: #264653;
        margin-bottom: 20px;
    }

    .texto-delegapp p {
        font-size: 1.1em;
        color: #3a3a3a;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .texto-delegapp ul {
        list-style: none;
        margin: 20px 0;
        padding: 0;
    }

    .texto-delegapp ul li {
        position: relative;
        padding-left: 28px;
        margin-bottom: 12px;
        font-size: 1.1em;
        color: #3a3a3a;
        line-height: 1.4;
    }

    .texto-delegapp ul li:before {
        content: "✔";
        position: absolute;
        left: 0;
        top: 0;
        color: #457b9d; /* Azul suave: frescura y confianza */
        font-weight: bold;
    }

    .texto-delegapp a {
        display: inline-block;
        padding: 14px 28px;
        background-color: #1d3557;
        color: white;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1em;
        transition: background 0.3s;
        margin-top: 10px;
    }

    .texto-delegapp a:hover {
        background-color: #457b9d;
    }

    .imagenes-delegapp {
        flex: 1 1 380px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        padding: 10px;
    }

    .imagenes-delegapp img {
        width: 100%;
        border-radius: 15px;
        height: 160px;
        object-fit: cover;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .texto-delegapp h1 {
            font-size: 2.2em;
        }

        .contenido-flex {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .imagenes-delegapp {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="contenedor-delegapp">
    <div class="contenido-flex">
        <!-- Texto principal -->
        <div class="texto-delegapp">
            <h1>REPORTES AL INSTANTE</h1>
            <p class="lead">
                Con <strong>DelegApp</strong> puedes reportar cualquier incidencia ciudadana en segundos, desde baches y alumbrado público hasta acumulación de basura y seguridad.
            </p>

            <ul>
                <li>Geolocalización automática: tu reporte llega con coordenadas exactas.</li>
                <li>Adjunta fotos o videos para mostrar el problema tal como está.</li>
                <li>Recibe notificaciones en tiempo real sobre el estado de tu reporte.</li>
                <li>Colabora con vecinos y autoridades para encontrar soluciones rápidas.</li>
            </ul>

            <p>
                Únete a cientos de ciudadanos comprometidos con la mejora de nuestra colonia.
                Con DelegApp, tu voz se escucha y se actúa.
            </p>

            <a href="registro.php">Crea tu Reporte</a>
        </div>

        <!-- Galería de imágenes -->
        <div class="imagenes-delegapp">
            <img src="alumbrado.jpg" alt="Alumbrado público">
            <img src="bache.avif" alt="Baches en calle">
            <img src="basura.jpeg" alt="Basura acumulada">
            <img src="seguridad.jpeg" alt="Seguridad pública">
        </div>
    </div>
</div>

<?php include("pie.php"); ?>
