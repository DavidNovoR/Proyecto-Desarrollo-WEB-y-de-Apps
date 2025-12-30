<?php
require_once __DIR__ . "/db.php";

$stmt = $pdo->query("SELECT * FROM songs LIMIT 10");
$canciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cargar playlists desde la base de datos
$stmt = $pdo->query("SELECT * FROM playlists ORDER BY fecha_creacion DESC LIMIT 5");
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);
