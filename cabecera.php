<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener el nombre del archivo actual
$currentPage = basename($_SERVER['PHP_SELF']);

// Conexión para obtener notificaciones si hay sesión activa
if (isset($_SESSION['usuario'], $_SESSION['tipo'], $_SESSION['user_id'])) {
    $mysqli = new mysqli("localhost", "root", "", "da");
    if (!$mysqli->connect_error) {
        $id_usuario = intval($_SESSION['user_id']);
        $notis = $mysqli->query("SELECT COUNT(*) AS total FROM notificaciones WHERE id_usuario = $id_usuario AND leido = 0");
        $notiCount = $notis->fetch_assoc()['total'] ?? 0;
        $mysqli->close();
    } else {
        $notiCount = 0;
    }
} else {
    $notiCount = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>DelegApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background-color: #1d3557; }
        .nav-link-text {
            color: #f1f1f1 !important;
            font-size: 1.1rem;
            margin-left: 0.5rem;
            transition: color 0.3s;
        }
        .nav-link-text:hover {
            color: #fca311 !important;
            text-decoration: underline;
        }
        .badge-noti {
            background-color: red;
            color: white;
            font-size: 0.7rem;
            margin-left: 5px;
            border-radius: 50%;
            padding: 3px 6px;
        }
        /* Estilo minimalista para el botón volver */
        .btn-back {
            background: transparent;
            border: none;
            color: #f1f1f1;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }
        .btn-back:hover {
            color: #fca311;
            text-decoration: underline;
        }
        .btn-back span {
            font-weight: bold;
            margin-left: 0.3rem;
        }
    </style>
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container d-flex align-items-center">
      <?php if ($currentPage !== 'index.php'): ?>
      <button type="button" class="btn-back" onclick="history.back()" aria-label="Volver atrás">
        ←<span>Volver</span>
      </button>
      <?php endif; ?>
      <a class="navbar-brand font-weight-bold mb-0" href="index.php">DelegApp</a>
      <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse"
              data-target="#mainNavbar" aria-controls="mainNavbar"
              aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav ml-auto align-items-center">
          <?php if (isset($_SESSION['usuario'])): ?>
            <li class="nav-item">
              <a href="notificaciones.php" class="nav-link nav-link-text">
                🔔 Notificaciones <?php if ($notiCount > 0): ?><span class="badge-noti"><?= $notiCount ?></span><?php endif; ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link nav-link-text" data-toggle="modal" data-target="#perfilModal">
                👤 <?= htmlspecialchars($_SESSION['usuario']) ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="cerrar.php" class="nav-link nav-link-text">Cerrar sesión</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a href="login_ciudadano.php" class="nav-link nav-link-text">Ciudadano</a>
            </li>
            <li class="nav-item">
              <a href="login_delegado.php" class="nav-link nav-link-text">Delegado</a>
            </li>
            <li class="nav-item">
              <a href="login.php" class="nav-link nav-link-text">Iniciar sesión</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>

<!-- El resto de tu código sigue igual -->
