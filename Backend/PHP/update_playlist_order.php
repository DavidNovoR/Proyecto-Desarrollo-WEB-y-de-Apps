<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'No autorizado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$playlist_id = $data['playlist_id'] ?? null;
$order = $data['order'] ?? [];

if (!$playlist_id || empty($order)) {
    echo json_encode(['status'=>'error','message'=>'Datos incompletos']);
    exit;
}

// Validar que la playlist pertenece al usuario
$stmt = $pdo->prepare("SELECT * FROM playlists WHERE id = ? AND user_id = ?");
$stmt->execute([$playlist_id, $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    echo json_encode(['status'=>'error','message'=>'Playlist no encontrada o no pertenece al usuario']);
    exit;
}

// Actualizar el orden en la base de datos
$pdo->beginTransaction();
$stmt = $pdo->prepare("UPDATE playlist_songs SET orden = ? WHERE playlist_id = ? AND song_id = ?");
foreach ($order as $item) {
    $stmt->execute([$item['orden'], $playlist_id, $item['id']]);
}
$pdo->commit();

echo json_encode(['status'=>'success']);
