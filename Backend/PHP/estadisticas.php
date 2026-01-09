<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT s.titulo, s.artista, COUNT(*) AS total
    FROM history h
    JOIN songs s ON h.song_id = s.id
    WHERE h.user_id = ?
    GROUP BY h.song_id
    ORDER BY total DESC
    LIMIT 1
");
$stmt->execute([$userId]);
$topSong = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT s.genero, COUNT(*) AS total
    FROM history h
    JOIN songs s ON h.song_id = s.id
    WHERE h.user_id = ?
    GROUP BY s.genero
    ORDER BY total DESC
    LIMIT 1
");
$stmt->execute([$userId]);
$topGenre = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT s.duracion
    FROM history h
    JOIN songs s ON h.song_id = s.id
    WHERE h.user_id = ?
");
$stmt->execute([$userId]);

$totalSeconds = 0;

while ($row = $stmt->fetch()) {
    [$min, $sec] = explode(":", $row['duracion']);
    $totalSeconds += ($min * 60) + $sec;
}

$hours = floor($totalSeconds / 3600);

$stmt = $pdo->prepare("
    SELECT DAYNAME(fecha_reproduccion) AS dia, COUNT(*) AS total
    FROM history
    WHERE user_id = ?
    GROUP BY dia
    ORDER BY total DESC
    LIMIT 1
");
$stmt->execute([$userId]);
$topDay = $stmt->fetch(PDO::FETCH_ASSOC);
$diasES = [
    'Monday'    => 'Lunes',
    'Tuesday'   => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday'  => 'Jueves',
    'Friday'    => 'Viernes',
    'Saturday'  => 'Sábado',
    'Sunday'    => 'Domingo'
];

$diaTraducido = $topDay
    ? ($diasES[$topDay['dia']] ?? $topDay['dia'])
    : 'Sin datos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Estadísticas - Musicfy</title>
  <link rel="stylesheet" href="../../Frontend/CSS/style.css" />
  <link rel="icon" type="image/x-icon" href="../img/logo_app.png">
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
      <li><a href="index.php"><img src="../../Frontend/img/icons/home_icon.png"> Inicio</a></li>
      <li><a href="library.php"><img src="../../Frontend/img//icons/library_music_icon.png"> Mi Biblioteca</a></li>
      <li><a href="favoritos.php"> <img src="../../Frontend/img/icons/favorite_icon.png"> Favoritos</li></a>
      <li><a href="estadisticas.php"><img src="../../Frontend/img/icons/analytics_icon.png"> Estadísticas</a></li>
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
    <h1 class="titulo-principal">Estadísticas de Música</h1>
    <section class="stats-grid">
      <div class="stat-card">
        <h2>🎵 Canción más escuchada</h2>
        <p>
        <?= $topSong
            ? '"' . htmlspecialchars($topSong['titulo']) . '" - ' . htmlspecialchars($topSong['artista'])
            : 'Sin datos'
        ?>
        </p>
      </div>
      <div class="stat-card">
        <h2>📈 Género favorito</h2>
        <p><?= $topGenre['genero'] ?? 'Sin datos' ?></p>
      </div>
      <div class="stat-card">
        <h2>⏱ Tiempo total de escucha</h2>
        <p><?= $hours ?> horas</p>
      </div>
      <div class="stat-card">
        <h2>📅 Día más activo</h2>
        <p><?= $diaTraducido ?></p>
      </div>
    </section>
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
