<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../Frontend/HTML/login.html");
    exit;
}
$data = require(__DIR__ . '/get_playlist.php');
$playlist = $data['playlist'];
$canciones = $data['canciones'];

// Obtener canciones favoritas del usuario
$stmtFav = $pdo->prepare("SELECT song_id FROM favorites WHERE user_id = ?");
$stmtFav->execute([$_SESSION["user_id"]]);
$favoritas = $stmtFav->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($playlist['nombre']) ?></title>
    <link rel="stylesheet" href="../../Frontend/CSS/style.css" />
    <link rel="icon" type="image/x-icon" href="../../Frontend/img/logo_app.png">
</head>

<body>
  <!-- NAVBAR -->
  <header>
    <nav class="navbar">
      <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
      <a class="logo-link" href="../../Backend/PHP/index.php">
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

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <ul>
      <li><a href="../../Backend/PHP/index.php"><img src="../../Frontend/img/icons/home_icon.png"> Inicio</a></li>
      <li><a href="library.php"><img src="../../Frontend/img/icons/library_music_icon.png"> Mi Biblioteca</a></li>
      <li><a href="favoritos.php"><img src="../../Frontend/img/icons/favorite_icon.png"> Favoritos</a></li>
      <li><a href="../../Frontend/HTML/estadisticas.html"><img src="../../Frontend/img/icons/analytics_icon.png"> Estadísticas</a></li>
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
    <section class="playlist-visual">
      <div class="playlist-header">
        <img src="../../Frontend/img/playlists/<?= htmlspecialchars($playlist['imagen']) ?>" alt="Portada de <?= htmlspecialchars($playlist['nombre']) ?>" class="playlist-cover">
        <div class="playlist-info">
          <h1><?= htmlspecialchars($playlist['nombre']) ?></h1>
          <p><?= htmlspecialchars($playlist['descripcion']) ?></p>
          <p><strong>Canciones:</strong> <?= $data['total_canciones'] ?></p>
          <p><strong>Duración total:</strong> <?= $data['duracion_total'] ?></p>
          <p><strong>Última modificación:</strong> <?= $data['ultima_modificacion'] ?></p>
          <p><strong>Fecha de creación:</strong> <?= $data['fecha_creacion'] ?></p>
          <?php if (isset($_GET['from']) && $_GET['from'] === 'library'): ?>
              <div class="playlist-actions">
                  <a href="editar_playlist.php?id=<?= $playlist['id'] ?>" class="btn-edit">Editar</a>
                  <a href="eliminar_playlist.php?id=<?= $playlist['id'] ?>" class="btn-delete">Eliminar</a>
              </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="playlist-songs">
      <div class="song-list-large" id="playlist-container">
        <?php foreach($canciones as $c): ?>
          <div class="song-card-large" data-audio="<?= htmlspecialchars($c['audio_url']) ?>" data-id="<?= htmlspecialchars($c['id']) ?>" draggable="true">
            <div class="drag-handle">
                    <img src="../../Frontend/img/icons/arrastrar.png" alt="Arrastrar">
            </div>
            <div class="section portada">
              <img src="<?= htmlspecialchars($c['portada']) ?>" alt="">
            </div>
            <div class="section titulo">
              <h3><?= htmlspecialchars($c['titulo']) ?></h3>
              <p>Reproducciones: <?= htmlspecialchars($c['reproducciones'] ?? 0) ?></p>
            </div>
            <div class="section autor">
              <p><?= htmlspecialchars($c['artista']) ?> • <?= htmlspecialchars($c['album'] ?? 'Sin álbum') ?></p>
            </div>
            <div class="section duracion">
              <span><?= htmlspecialchars($c['duracion'] ?? '0:00') ?></span>
              <?php
              $esFavorita = in_array($c['id'], $favoritas);
              $iconoLike = $esFavorita ? "like-red.png" : "like.png";
              ?>
              <button class="like-bt" data-song="<?= $c['id'] ?>">
                  <img src="../../Frontend/img/icons/<?= $iconoLike ?>" alt="">
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <!-- REPRODUCTOR -->
  <div class="audio-player">
      <img src="../../Frontend/img/logo_app.png" alt="Portada" class="player-cover">
      <div class="player-info">
        <h4></h4>
        <p></p>
      </div>
      <div class="player-controls">
        <button class="player-prev">
          <img src="../../Frontend/img/icons/previous_song_icon.png" alt="">
        </button>
        <button class="player-play">
          <img class="player-play-icon" src="../../Frontend/img/icons/play_circle_icon.png" alt="">
        </button>
        <button class="player-next">
          <img src="../../Frontend/img/icons/next_song_icon.png" alt="">
        </button>
      </div>
      <div class="player-progress">
        <span id="current-time">0:00</span>
        <input type="range" id="progress-bar" min="0" max="100" value="0">
        <span id="total-time">0:00</span>
        <img
          class="volume-icon"
          src='../../Frontend/img/icons/altavoz.png'
          alt='altavoz'
          style="cursor:pointer;"
        >
        <div class="player-volume">
          <input type="range" id="volume-bar" min="0" max="100" value="80">
        </div>
      </div>
    </div>

  <!-- FOOTER -->
  <footer>
    <p>© 2025-2026 PlayListApp - Proyecto académico</p>
  </footer>
  <script>
      const PLAYLIST_ID = <?= $playlist['id'] ?>;
  </script>
  <script src="../../Frontend/JS/playlist_sort.js"></script>
  <script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('active');
    }
  </script>
  <script> 
    const songList = <?= json_encode($canciones, JSON_UNESCAPED_UNICODE) ?>;
  </script>

  <script src="../../Frontend/JS/player.js"></script>
  <script src="../../Frontend/JS/like.js"></script>
  <script src="../../Frontend/JS/audio_line.js"></script>