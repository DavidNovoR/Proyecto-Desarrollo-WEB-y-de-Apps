<?php
require_once __DIR__ . "/db.php";

$id           = $_POST['id'] ?? null;
$nombre       = $_POST['nombre'] ?? '';
$descripcion  = $_POST['descripcion'] ?? '';
$visibilidad  = $_POST['visibilidad'] ?? 'privada';

$quitar       = $_POST['quitar_canciones'] ?? [];
$agregar      = $_POST['agregar_canciones'] ?? [];

if (!$id) {
    die("ID inválido");
}

// 1. Procesar imagen nueva
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

// 2. Actualizar playlist (forzando modificación)
if ($imagen) {
    $sql = "UPDATE playlists 
            SET nombre = ?, descripcion = ?, visibilidad = ?, imagen = ?, fecha_creacion = fecha_creacion 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $descripcion, $visibilidad, $imagen, $id]);
} else {
    $sql = "UPDATE playlists 
            SET nombre = ?, descripcion = ?, visibilidad = ?, fecha_creacion = fecha_creacion 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $descripcion, $visibilidad, $id]);
}

// 3. Quitar canciones
if (!empty($quitar)) {
    $sql = "DELETE FROM playlist_songs WHERE playlist_id = ? AND song_id = ?";
    $stmt = $pdo->prepare($sql);

    foreach ($quitar as $song_id) {
        $stmt->execute([$id, $song_id]);
    }
}

// 4. Agregar canciones
if (!empty($agregar)) {
    $sql = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);

    foreach ($agregar as $song_id) {
        $stmt->execute([$id, $song_id]);
    }
}

// 5. Redirigir
header("Location: playlist.php?id=$id&from=library");
exit;
