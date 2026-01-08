<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT
        h.fecha_reproduccion,
        s.titulo,
        s.portada
    FROM history h
    JOIN songs s ON h.song_id = s.id
    WHERE h.user_id = ?
    ORDER BY h.fecha_reproduccion DESC
");
$stmt->execute([$userId]);
$historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Historial - Musicfy</title>
  <link rel="stylesheet" href="../../Frontend/CSS/style.css" />
  <link rel="icon" type="image/x-icon" href="../../Frontend/img/logo_app.png">
</head>
<body>

<header>
  <nav class="navbar">
      <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

      <a href="index.php" class="logo-link">
          <img class="logo_img" src="../../Frontend/img/logo_app.png" alt="">
      </a>

      <div class="logo">Musicfy</div>

      <form action="busqueda.php" method="get" class="bloque-busqueda">
              <input type="text" name="q" placeholder="Buscar canciones, artistas, álbumes..." class="search-bar" />
              <button type="submit" class="search-btn">
                  <img src="../../Frontend/img/icons/search_icon.png" alt="icono de lupa de búsqueda">
              </button>
            </form>

      <?php if (isset($_SESSION["usuario"])): ?>
          <div class="user-info">
              <span class="username">👤 <?= htmlspecialchars($_SESSION["usuario"]) ?></span>
              <a href="logout.php" class="logout-btn">Cerrar sesión</a>
          </div>
      <?php else: ?>
          <a href="../../Frontend/HTML/LoginScreen.html" class="login-btn">Iniciar sesión</a>
      <?php endif; ?>
  </nav>
</header>


<aside class="sidebar">
  <ul>
        <li><a href="index.php"><img src="../../Frontend/img/icons/home_icon.png" alt="icono de casa">  Inicio</a></li>
        <li><a href="library.php"><img src="../../Frontend/img/icons/library_music_icon.png" alt="icono de Playlists">  Mi Biblioteca</a></li>
        <li><a href="favoritos.php"><img src="../../Frontend/img/icons/favorite_icon.png" alt="icono de favoritos">  Favoritos</a></li>
        <li><a href="estadisticas.php"><img src="../../Frontend/img/icons/analytics_icon.png" alt="icono de estadisticas">  Estadísticas</a></li>
        <li><a href="historial.php"><img src="../../Frontend/img/icons/history_icon.png" alt="icono de historial">  Historial</a></li>
        <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin"): ?>
                    <li class="sidebar-admin">
                      <a href="gestionar.php">
                        <img src="../../Frontend/img/icons/gestionar_icon.png">
                        Gestionar
                      </a>
                    </li>
                <?php endif; ?>
      </ul>
</aside>

<main>
  <h1 class="titulo-principal">Historial de Música</h1>

  <div class="historial-list">

    <?php if (empty($historial)): ?>
      <p style="color:#ccc; text-align:center;">Aún no has escuchado ninguna canción</p>
    <?php endif; ?>

    <?php foreach ($historial as $item): ?>
      <div class="historial-item">
        <img src="<?= htmlspecialchars($item['portada']) ?>" alt="Portada">
        <div class="historial-info">
          <h3><?= htmlspecialchars($item['titulo']) ?></h3>
          <p><?= date("d/m/Y H:i", strtotime($item['fecha_reproduccion'])) ?></p>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</main>

<footer>
  <p>© 2025-2026 PlayListApp - Proyecto académico</p>
</footer>
<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
}
</script>
</body>
</html>
