<?php
  // pie.php
  // Asegúrate de incluirlo justo antes de cerrar </body> en tus plantillas
?>
<!-- Font Awesome para iconos -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>

<style>
  /* Estilos del Footer */
  .site-footer {
    background: #111;
    color: #ccc;
    padding: 40px 0 20px;
    font-family: 'Segoe UI', sans-serif;
  }
  .footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 1200px;
    margin: auto;
    gap: 40px;
    padding: 0 20px;
  }
  .footer-left,
  .footer-right {
    flex: 1 1 300px;
  }
  .footer-logo {
    max-width: 150px;
    margin-bottom: 20px;
  }
  .footer-left p {
    line-height: 1.6;
    margin-bottom: 20px;
  }
  .btn-footer {
    display: inline-block;
    padding: 12px 24px;
    background: #0056ff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s;
  }
  .btn-footer:hover {
    background: #0041c4;
  }
  .footer-right h3 {
    font-size: 1.4em;
    margin-bottom: 20px;
    color: #fff;
  }
  .contact-info {
    list-style: none;
    padding: 0;
  }
  .contact-info li {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    font-size: 1em;
  }
  .contact-info li i {
    margin-right: 10px;
    color: #0056ff;
    font-size: 1.1em;
  }
  .footer-bottom {
    border-top: 1px solid #333;
    margin-top: 30px;
    padding-top: 15px;
    text-align: center;
  }
  .footer-bottom p {
    margin: 0;
    font-size: 0.9em;
    color: #777;
  }
  @media (max-width: 768px) {
    .footer-container {
      flex-direction: column;
      align-items: center;
    }
    .footer-left,
    .footer-right {
      text-align: center;
    }
    .contact-info li {
      justify-content: center;
    }
  }
</style>

<footer class="site-footer">
  <div class="footer-container">
    <!-- IZQUIERDA -->
    <div class="footer-left">
      <h4 class="footer-logo">DELEGAPP</h4>
      <p>
        En <strong>DelegApp</strong> conectamos a la comunidad con las autoridades
        para resolver tus reportes de forma rápida y transparente. ¡Juntos
        podemos mejorar nuestro entorno!
      </p>
    </div>

    <!-- DERECHA -->
    <div class="footer-right">
      <h3>Contacto</h3>
      <ul class="contact-info">
        <li>
          <i class="fas fa-map-marker-alt"></i>
          Av. Central Oriente #836 int. 10, Col. Centro, Tuxtla Gutiérrez, Chiapas
        </li>
        <li>
          <i class="fas fa-envelope"></i>
          delegapp@gmail.com
        </li>
        <li>
          <i class="fas fa-clock"></i>
          Lun–Sab: 09:00–18:00
        </li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© 2025 DelegApp. Todos los derechos reservados.</p>
  </div>
</footer>
