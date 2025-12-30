<?php
require_once __DIR__ . "/db.php";

// 1. Obtener ID
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Playlist no encontrada");
}

// 2. Obtener datos de la playlist
$sql = "SELECT * FROM playlists WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$playlist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$playlist) {
    die("Playlist no encontrada");
}

// 3. Obtener canciones vinculadas
$sql = "SELECT s.id, s.titulo, s.artista 
        FROM songs s
        INNER JOIN playlist_songs ps ON ps.song_id = s.id
        WHERE ps.playlist_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$canciones_vinculadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Obtener todas las canciones
$sql = "SELECT id, titulo, artista FROM songs ORDER BY titulo";
$stmt = $pdo->query($sql);
$todas_canciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. IDs vinculados
$ids_vinculados = array_column($canciones_vinculadas, 'id');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Musicfy - Editar Playlist</title>
  <link rel="stylesheet" href="../../Frontend/CSS/style.css" />
  <link rel="icon" type="image/x-icon" href="../../Frontend/img/logo_app.png">
</head>

<body>

  <!-- NAVBAR -->
  <header>
    <nav class="navbar">
      <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
      <a href="../../Backend/PHP/index.php" class="logo-link">
        <img class="logo_img" src="../../Frontend/img/logo_app.png" alt="">
      </a>
      <div class="logo">Musicfy</div>
      <form action="../../Backend/PHP/busqueda.php" method="get" class="bloque-busqueda">
          <input type="text" name="q" placeholder="Buscar canciones, artistas, álbumes..." class="search-bar" />
          <button type="submit" class="search-btn">
              <img src="../../Frontend/img/icons/search_icon.png" alt="icono de lupa de búsqueda">
          </button>
      </form>
      <button class="login-btn">Iniciar sesión</button>
    </nav>
  </header>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <ul>
      <li><a href="../../Backend/PHP/index.php"><img src="../../Frontend/img/icons/home_icon.png"> Inicio</a></li>
      <li><img src="../../Frontend/img/icons/library_music_icon.png"> Mi Biblioteca</li>
      <li><img src="../../Frontend/img/icons/favorite_icon.png"> Favoritos</li>
      <li><a href="estadisticas.html"><img src="../../Frontend/img/icons/analytics_icon.png"> Estadísticas</a></li>
    </ul>
  </aside>

  <!-- MAIN -->
  <main>
    <section class="playlist-editor">
    <h2>Editar Playlist</h2>

    <form action="update_playlist.php" method="POST" enctype="multipart/form-data">
        <div class="contenedor">

            <input type="hidden" name="id" value="<?= $playlist['id'] ?>">

            <!-- Título -->
            <div class="bloques">
                <label for="nombre">Título de la Playlist:</label>
                <input type="text" id="nombre" name="nombre" maxlength="128"
                    value="<?= htmlspecialchars($playlist['nombre']) ?>" required>
            </div>

            <!-- Descripción -->
            <div class="bloques">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" maxlength="1000"><?= htmlspecialchars($playlist['descripcion']) ?></textarea>
            </div>

            <!-- Imagen actual -->
            <div class="bloques">
                <label>Imagen actual:</label>
                <img src="../../Frontend/img/playlists/<?= htmlspecialchars($playlist['imagen']) ?>" width="150">
            </div>

            <!-- Nueva imagen -->
            <div class="bloques">
                <label for="imagen">Cambiar imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
            </div>

            <!-- Visibilidad -->
            <div class="bloques">
                <label for="visibilidad">Visibilidad:</label>
                <select id="visibilidad" name="visibilidad" required>
                    <option value="privada" <?= $playlist['visibilidad'] === 'privada' ? 'selected' : '' ?>>Privada</option>
                    <option value="publica" <?= $playlist['visibilidad'] === 'publica' ? 'selected' : '' ?>>Pública</option>
                </select>
            </div>

            <!-- Canciones actuales -->
            <div class="bloques">
                <label>Canciones en la Playlist: (Seleccionar para eliminar)</label>
                <?php if (empty($canciones_vinculadas)): ?>
                    <p>No hay canciones en esta playlist.</p>
                <?php else: ?>
                    <?php foreach ($canciones_vinculadas as $c): ?>
                        <label>
                            <input type="checkbox" name="quitar_canciones[]" value="<?= $c['id'] ?>">
                            <?= htmlspecialchars($c['titulo']) ?> - <?= htmlspecialchars($c['artista']) ?>
                        </label><br>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Añadir canciones -->
            <div class="bloques">
                <label>Añadir canciones:</label>
                <select name="agregar_canciones[]" multiple size="6" class="selector-canciones">
                    <?php foreach ($todas_canciones as $c): ?>
                        <?php if (!in_array($c['id'], $ids_vinculados)): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= htmlspecialchars($c['titulo']) ?> - <?= htmlspecialchars($c['artista']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <small class="nota">Puedes seleccionar varias canciones manteniendo pulsado CTRL o SHIFT.</small>
            </div>

            <!-- Botón -->
            <button type="submit">Guardar Cambios</button>

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
      document.querySelector('.sidebar').classList.toggle('active');
    }
  </script>

</body>
</html>
