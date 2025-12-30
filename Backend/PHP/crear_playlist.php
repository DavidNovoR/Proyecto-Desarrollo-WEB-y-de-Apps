<?php
session_start();
require_once __DIR__ . "/db.php";

//Verificar sesión
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "Debes iniciar sesión."]);
    exit;
}

$user_id = $_SESSION["user_id"];
$rol     = $_SESSION["rol"] ?? "user"; // por defecto user

//Recoger datos del formulario
$nombre      = $_POST['nombre']        ?? '';
$descripcion = $_POST['descripcion']   ?? '';
$visibilidad = $_POST['visibilidad']   ?? 'privada';
$canciones   = $_POST['canciones']     ?? [];

//Validar visibilidad según rol
if ($visibilidad === "publica" && $rol !== "admin") {
    echo json_encode([
        "status" => "error",
        "message" => "Solo los administradores pueden crear playlists públicas."
    ]);
    exit;
}

//Procesar imagen
$imagen = null;

if (!empty($_FILES['imagen']['name'])) {

    $imagen = basename($_FILES['imagen']['name']);
    $destinoCarpeta = __DIR__ . "/../../Frontend/img/playlists/";

    if (!is_dir($destinoCarpeta)) {
        mkdir($destinoCarpeta, 0777, true);
    }

    $rutaDestino = $destinoCarpeta . $imagen;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imagen = null;
    }
}

//Insertar playlist
$sql = "INSERT INTO playlists (user_id, nombre, descripcion, imagen, visibilidad) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $nombre, $descripcion, $imagen, $visibilidad]);

$playlist_id = $pdo->lastInsertId();

//Insertar canciones asociadas
if (!empty($canciones)) {
    $sql_cancion = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES (?, ?)";
    $stmt_cancion = $pdo->prepare($sql_cancion);

    foreach ($canciones as $song_id) {
        $stmt_cancion->execute([$playlist_id, $song_id]);
    }
}

//Respuesta
echo json_encode(["status" => "success"]);
exit;
