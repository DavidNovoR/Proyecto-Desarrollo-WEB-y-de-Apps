<?php
require_once(__DIR__ . '/db.php');

$sql = "SELECT id, nombre, descripcion, imagen FROM playlists ORDER BY id DESC";
$stmt = $pdo->query($sql);

$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

return [
    'playlists' => $playlists
];