<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Musicfy - Crear Playlist</title>
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
      <li><a href='library.php'><img src="../../Frontend/img/icons/library_music_icon.png">Mi Biblioteca</a></li>
      <li><a href='favoritos.php'><img src="../../Frontend/img/icons/favorite_icon.png"> Favoritos</a></li>
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

  <!-- MAIN SOLO CAMBIA ESTO -->
  <main>
    <section class="playlist-editor">
    <h2>Crear mi nueva Playlist</h2>
    <form id="formPlaylist" enctype="multipart/form-data">
        <div class="contenedor">
            <!-- Título -->
            <div class="bloques">
                <label for="nombre">Título de la Playlist:</label>
                <input type="text" id="nombre" name="nombre" maxlength="128" required>
            </div>

            <!-- Descripción -->
            <div class="bloques">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" maxlength="1000" placeholder="Describe tu playlist..."></textarea>
            </div>

            <!-- Imagen -->
            <div class="bloques">
                <label for="imagen">Imagen de portada:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
            </div>

            <!-- Visibilidad -->
            <div class="bloques">
                <label for="visibilidad">Visibilidad:</label>
                <select id="visibilidad" name="visibilidad" required>
                <option value="privada">Privada</option>
                <option value="publica">Pública</option>
                </select>
            </div>

            <!-- Selección de canciones -->
            <div class="bloques">
                <label for="canciones">Añadir canciones a la Playlist:</label>
                <select id="canciones" name="canciones[]" multiple size="6" class="selector-canciones">
                    <!-- Aquí se llenará automáticamente -->
                </select>
                <small class="nota">Puedes seleccionar varias canciones manteniendo pulsado CTRL o SHIFT.</small>
            </div>

            <!-- Botón de envío -->
            <button type="submit">Guardar Playlist</button>
        </div>
    </form>
    </section>
  </main>

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
  <script src="../../Frontend/JS/canciones_crear_playlist.js"></script>
</body>
</html>
