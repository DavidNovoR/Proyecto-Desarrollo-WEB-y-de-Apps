<?php
session_start();

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_user"])) {
    $_SESSION["user_id"] = $_COOKIE["remember_user"];
}
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../Frontend/HTML/LoginScreen.html");
    exit;
}

require_once(__DIR__ . '/songs.php');
$data = require(__DIR__ . '/get_playlists.php');
$playlist = $data['playlists'];

$mapa_generos = [];
foreach ($playlist as $p) { 
    $nombre = strtolower(trim($p['nombre'])); 
    $mapa_generos[$nombre] = $p['id']; 
}

$playlists = array_slice($playlists, 0, 4);
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
  <title>Musicfy - App Music</title>
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
      <div class="logo"> Musicfy</div>
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
      <li><a href="../../Frontend/HTML/estadisticas.html"><img src="../../Frontend/img/icons/analytics_icon.png" alt="icono de estadisticas">  Estadísticas</a></li>
    </ul>
  </aside>

  <main>
    <section class="quick-picks">
      <div class="filters">
        <a href="playlist.php?id=<?= $mapa_generos['pop'] ?? '#' ?>"><button>🎤 Pop</button></a>
        <a href="playlist.php?id=<?= $mapa_generos['metal'] ?? '#' ?>"><button>🎸 Metal</button></a>
        <a href="playlist.php?id=<?= $mapa_generos['rock'] ?? '#' ?>"><button>🤘 Rock</button></a>
        <a href="playlist.php?id=<?= $mapa_generos['classical'] ?? '#' ?>"><button>🎻 Classical</button></a>
        <a href="playlist.php?id=<?= $mapa_generos['choral'] ?? '#' ?>"><button>🎼 Choral</button></a>
        <a href="playlist.php?id=<?= $mapa_generos['otros'] ?? '#' ?>"><button>🎶 Otros</button></a>
      </div>
      <h2>Top Playlists</h2>
      <div class="playlist-list">
        <?php foreach ($playlists as $p): ?>
          <a href="playlist.php?id=<?= $p['id'] ?>" class="song-card" style="text-decoration:none; color:white;">
            <img src="../../Frontend/img/playlists/<?= htmlspecialchars($p['imagen']) ?>" alt="Portada playlist" />
            <div class="song-info">
              <h3><?= htmlspecialchars($p['nombre']) ?></h3>
              <p><?= htmlspecialchars($p['descripcion']) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <h2>Top Canciones</h2>
    <div class="song-list-large">
      <?php foreach($canciones as $c): ?>
        <div class="song-card-large" data-audio="<?= htmlspecialchars($c['audio_url']) ?>">
          <div class="section portada">
            <img src="<?= htmlspecialchars($c['portada'] ?? 'img/cover.jpg') ?>" alt="Portada álbum" />
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
      <h2>Playlists Recomendadas</h2>
      <div class="song-list">
        <div class="song-card">
          <img src="../../Frontend/img/logo_app.png" alt="Portada álbum" />
          <div class="song-info">
            <h3>Título de la canción</h3>
            <p>Artista • Álbum</p>
            <p>Reproducciones: 1234</p>
          </div>
        </div>
      </div>
      <h2>Canciones Recomendadas</h2>
      <div class="song-list">
        <div class="song-card">
          <img src="../../Frontend/img/logo_app.png" alt="Portada álbum" />
          <div class="song-info">
            <h3>Título de la canción</h3>
            <p>Artista • Álbum</p>
            <p>Reproducciones: 1234</p>
          </div>
        </div>
      </div>
    </section>
  </main>

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
      <div class="player-volume">
        <input type="range" id="volume-bar" min="0" max="100" value="80">
      </div>
    </div>
  </div>

  <footer>
    <p>© 2025-2026 PlayListApp - Proyecto académico</p>
  </footer>

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
  <script src="../../Frontend/JS/volume_line.js"></script>
</body>
</html>