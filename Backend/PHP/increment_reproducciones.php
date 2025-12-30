<?php
require_once(__DIR__ . '/db.php');

$songId = $_POST['id'] ?? null;

if (!$songId) {
    http_response_code(400);
    echo "ID de canción no especificado.";
    exit;
}

$stmt = $pdo->prepare("UPDATE songs SET reproducciones = reproducciones + 1 WHERE id = ?");
$stmt->execute([$songId]);

echo "OK";
