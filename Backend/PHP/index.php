<?php
    require_once(__DIR__ . '/songs.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Musicfy - App Music</title>
  <link rel="stylesheet" href="../../Frontend/CSS/style.css" />
</head>
<body>
  <header>
    <nav class="navbar">
      <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
      <img class="logo_img" src="../../Frontend/img/logo_app.png" alt="">
      <div class="logo"> Musicfy</div>
      <input type="text" placeholder="Buscar canciones, artistas, álbumes..." class="search-bar" />
      <button class="search-btn"><img src="../../Frontend/img/icons/search_icon.png" alt="icono de lupa de busqueda"></button>
      <button class="login-btn">Iniciar sesión</button>
    </nav>
  </header>

  <aside class="sidebar">
    <ul>
      <li><img src="../../Frontend/img/icons/home_icon.png" alt="icono de casa">  Inicio</li>
      <li><img src="../../Frontend/img/icons/library_music_icon.png" alt="icono de Playlists">  Mi Biblioteca</li>
      <li><img src="../../Frontend/img/icons/favorite_icon.png" alt="icono de favoritos">  Favoritos</li>
      <li><img src="../../Frontend/img/icons/analytics_icon.png" alt="icono de estadisticas">  Estadísticas</li>
    </ul>
  </aside>

  <main>
    <section class="quick-picks">
      <div class="filters">
        <button>🎧 Estudio</button>
        <button>🔥 Energía</button>
        <button>😌 Relax</button>
        <button>🏋️‍♂️ Ejercicio</button>
        <button>🎉 Fiesta</button>
        <button>🧘 Meditación</button>
      </div>
      <h2>Top Playlists</h2>
      <div class="song-list">
        <div class="song-card">
          <img src="cover.jpg" alt="Portada álbum" />
          <div class="song-info">
            <h3>Título de la canción</h3>
            <p>Artista • Álbum</p>
            <p>Reproducciones: 1234</p>
          </div>
        </div>
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
          </div>
          <div class="section autor">
            <p><?= htmlspecialchars($c['artista']) ?> • <?= htmlspecialchars($c['album'] ?? 'Sin álbum') ?></p>
          </div>
          <div class="section duracion">
            <span><?= htmlspecialchars($c['duracion'] ?? '0:00') ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
      <h2>Playlists Recomendadas</h2>
      <div class="song-list">
        <div class="song-card">
          <img src="cover.jpg" alt="Portada álbum" />
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
          <img src="cover.jpg" alt="Portada álbum" />
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
    <img src="img/cover.jpg" alt="Portada" class="player-cover">
    <div class="player-info">
      <h4>Trio HxC</h4>
      <p>TriFace.Premiers Jets</p>
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
  <script src="../../Frontend/JS/audio_line.js"></script>
  <script src="../../Frontend/JS/volume_line.js"></script>
</body>
</html>