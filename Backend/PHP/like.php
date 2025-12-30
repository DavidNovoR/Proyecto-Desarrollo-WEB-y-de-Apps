<?php
require_once(__DIR__ . '/db.php');

session_start();
$user_id = $_SESSION['user_id'] ?? null;
$song_id = $_POST['song_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$user_id || !$song_id || !$action) {
    http_response_code(400);
    echo "Datos incompletos";
    exit;
}

if ($action === 'add') {
    $stmt = $pdo->prepare("INSERT IGNORE INTO favorites (user_id, song_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $song_id]);
    echo "Añadido";
} elseif ($action === 'remove') {
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND song_id = ?");
    $stmt->execute([$user_id, $song_id]);
    echo "Eliminado";
} else {
    http_response_code(400);
    echo "Acción inválida";
}
