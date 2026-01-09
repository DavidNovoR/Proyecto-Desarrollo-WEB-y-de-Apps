<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../Frontend/HTML/login.html");
    exit;
}

$data = require(__DIR__ . '/get_user_playlists.php');
$playlists = $data['playlists'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Mi Biblioteca - Musicfy</title>
        <link rel="stylesheet" href="../../Frontend/CSS/style.css" />
        <link rel="icon" type="image/x-icon" href="../../Frontend/img/logo_app.png">
    </head>

    <body>
        <!-- NAVBAR -->
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
                <a href="../../Frontend/HTML/login.html" class="login-btn">Iniciar sesión</a>
            <?php endif; ?>
            </nav>
        </header>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <ul>
            <li><a href="index.php"><img src="../../Frontend/img/icons/home_icon.png"> Inicio</a></li>
            <li><a href="library.php"><img src="../../Frontend/img/icons/library_music_icon.png"> Mi Biblioteca</a></li>
            <li><a href="favoritos.php"><img src="../../Frontend/img/icons/favorite_icon.png"> Favoritos</a></li>
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

        <!-- CONTENIDO PRINCIPAL -->
        <main>
            <section class="quick-picks">
                <h1 class='titulo-principal'>Mis PlayList</h1>
            <h2><a href="crear_playlist_html.php" class="titulo" id='crear-playlist'>Crear Playlist</a></h2>
            <div class="playlist-list">
                <?php foreach ($playlists as $p): ?>
                <a href="playlist.php?id=<?= $p['id'] ?>&from=library" class="song-card">
                    <img src="../../Frontend/img/playlists/<?= htmlspecialchars($p['imagen']) ?>" alt="Portada playlist" />
                    <div class="song-info">
                    <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                    <p><?= htmlspecialchars($p['descripcion']) ?></p>
                    </div>
                </a>
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
            function toggleSidebar() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.toggle('active');
            }
        </script>

        <script src="../../Frontend/JS/player.js"></script>
        <script src="../../Frontend/JS/like.js"></script>
        <script src="../../Frontend/JS/audio_line.js"></script>
        <script src="../../Frontend/JS/volume_line.js"></script>
    </body>
</html>
