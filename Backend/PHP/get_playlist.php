<?php
require_once(__DIR__ . '/db.php');

$playlistId = $_GET['id'] ?? null;
if (!$playlistId) {
    die("Playlist no especificada.");
}

// Obtener playlist
$stmt = $pdo->prepare("SELECT * FROM playlists WHERE id = ?");
$stmt->execute([$playlistId]);
$playlist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$playlist) {
    die("Playlist no encontrada.");
}

$nombre = strtolower(trim($playlist['nombre']));
$generos_validos = [ "metal", "pop", "rock", "classical", "choral", "otros" ];

if (in_array($nombre, $generos_validos)) {

    // 1. Determinar género real
    $genero_buscar = ($nombre === "otros") ? "Unknown" : ucfirst($nombre);

    // 2. Obtener canciones del género
    $stmt = $pdo->prepare("SELECT id, titulo, artista, album, portada, duracion, audio_url 
                            FROM songs WHERE genero = ?");
    $stmt->execute([$genero_buscar]);
    $canciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Obtener canciones ya vinculadas
    $stmt = $pdo->prepare("SELECT song_id FROM playlist_songs WHERE playlist_id = ?");
    $stmt->execute([$playlistId]);
    $ya_vinculadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 4. Insertar solo las que falten
    $sqlInsert = $pdo->prepare("INSERT INTO playlist_songs (playlist_id, song_id) VALUES (?, ?)");

    $maxOrdenStmt = $pdo->prepare("SELECT IFNULL(MAX(orden), 0) FROM playlist_songs WHERE playlist_id = ?");
    $maxOrdenStmt->execute([$playlistId]);
    $maxOrden = $maxOrdenStmt->fetchColumn();

    foreach ($canciones as $c) {
        if (!in_array($c['id'], $ya_vinculadas)) {
            $maxOrden++;
            $sqlInsert->execute([$playlistId, $c['id'], $maxOrden]);
        }
    }
} else {
    $stmt = $pdo->prepare("
        SELECT s.*
        FROM songs s
        JOIN playlist_songs ps ON s.id = ps.song_id
        WHERE ps.playlist_id = ?
        ORDER BY ps.orden ASC
    ");
    $stmt->execute([$playlistId]);
    $canciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Cálculo de estadísticas
$total_canciones = count($canciones);
$total_segundos = 0;

foreach ($canciones as $c) {
    if (!empty($c['duracion']) && strpos($c['duracion'], ":") !== false) {
        list($min, $sec) = explode(":", $c['duracion']);
        $total_segundos += ((int)$min * 60) + (int)$sec;
    }
}

$horas = floor($total_segundos / 3600);
$minutos = floor(($total_segundos % 3600) / 60);
$segundos = $total_segundos % 60;

$duracion_total = ($horas > 0)
    ? sprintf("%d:%02d:%02d", $horas, $minutos, $segundos)
    : sprintf("%d:%02d", $minutos, $segundos);

// Devolver datos enriquecidos
return [
    "playlist" => $playlist,
    "canciones" => $canciones,
    "total_canciones" => $total_canciones,
    "duracion_total" => $duracion_total,
    "ultima_modificacion" => $playlist['ultima_modificacion'],
    "fecha_creacion" => $playlist['fecha_creacion']

];
