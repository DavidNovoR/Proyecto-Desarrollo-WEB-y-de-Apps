<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestionar - Musicfy</title>
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
<h1 class="titulo-principal">Gestión de Música</h1>

<section class="playlist-editor">
<h2>Añadir nueva canción</h2>

<form action="add_song.php" method="POST" enctype="multipart/form-data">
    <div class="bloques">
        <label>Archivo de audio</label>
        <input type="file" name="audio_file" accept="audio/*" required>
    </div>

    <div class="bloques">
        <label>Portada</label>
        <input type="file" name="portada_file" accept="image/*" required>
    </div>

    <div class="bloques">
        <label>Título</label>
        <input type="text" name="titulo" required>
    </div>

    <div class="bloques">
        <label>Artista</label>
        <input type="text" name="artista" required>
    </div>

    <div class="bloques">
        <label>Álbum</label>
        <input type="text" name="album">
    </div>

    <div class="bloques">
        <label>Duración</label>
        <input type="text" name="duration">
    </div>

    <div class="bloques">
        <label>Género</label>
        <input type="text" name="genero" required>
    </div>

    <div class="bloques">
        <label>Licencia</label>
        <input type="text" name="licencia" required>
    </div>

    <button type="submit">Añadir canción</button>
</form>
</section>

<section class="playlist-editor">
 <h2>Eliminar canciones</h2>

 <form>
     <div class="bloques" id="bloque-canciones">
         <label>Selecciona canciones</label>
         <select id="canciones" name="canciones[]" multiple size="8" class="selector-canciones">
         </select>
     </div>
    <br>
     <button type="submit" style="background:#c0392b;" id="boton-eliminar-canciones">
         Eliminar canciones
     </button>
 </form>
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
<script src="../../Frontend/JS/gestionar.js"></script>
<script src="../../Frontend/JS/canciones_gestionar.js"></script>
</body>
</html>
