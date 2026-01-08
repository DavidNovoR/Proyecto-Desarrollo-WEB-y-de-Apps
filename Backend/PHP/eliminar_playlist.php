<?php
require_once __DIR__ . "/db.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID inválido");
}

$sql = "DELETE FROM playlist_songs WHERE playlist_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

$sql = "DELETE FROM playlists WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: library.php");
exit;
