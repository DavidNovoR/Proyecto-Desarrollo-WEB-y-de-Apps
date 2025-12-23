<?php
require_once __DIR__ . "/db.php";

// ===============================
// 1. Recoger datos del formulario
// ===============================
$nombre      = $_POST['nombre']        ?? '';
$descripcion = $_POST['descripcion']   ?? '';
$visibilidad = $_POST['visibilidad']   ?? 'privada';
$canciones   = $_POST['canciones']     ?? [];   // array o vacío
$user_id     = 1; // temporal hasta que tengas login

// ===============================
// 2. Procesar imagen
// ===============================
$imagen = null;

if (!empty($_FILES['imagen']['name'])) {

    $imagen = basename($_FILES['imagen']['name']);

    // Ruta REAL donde guardar la imagen
    $destinoCarpeta = __DIR__ . "/../../Frontend/img/playlists/";

    // Crear carpeta si no existe
    if (!is_dir($destinoCarpeta)) {
        mkdir($destinoCarpeta, 0777, true);
    }

    $rutaDestino = $destinoCarpeta . $imagen;

    // Mover archivo subido
    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imagen = null; // si falla, no guardamos imagen
    }
}

// ===============================
// 3. Insertar playlist en la BD
// ===============================
$sql = "INSERT INTO playlists (user_id, nombre, descripcion, imagen, visibilidad) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $nombre, $descripcion, $imagen, $visibilidad]);

$playlist_id = $pdo->lastInsertId();

// ===============================
// 4. Insertar canciones asociadas
// ===============================
if (!empty($canciones)) {

    $sql_cancion = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES (?, ?)";
    $stmt_cancion = $pdo->prepare($sql_cancion);

    foreach ($canciones as $song_id) {
        $stmt_cancion->execute([$playlist_id, $song_id]);
    }
}

// ===============================
// 5. Respuesta para el fetch()
// ===============================
echo "ok";
