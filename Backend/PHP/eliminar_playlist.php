<?php
require_once __DIR__ . "/db.php";

// 1. Obtener ID
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID inválido");
}

// 2. Eliminar canciones asociadas
$sql = "DELETE FROM playlist_songs WHERE playlist_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

// 3. Eliminar playlist
$sql = "DELETE FROM playlists WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

// 4. Redirigir a la biblioteca
header("Location: library.php");
exit;
