<?php
require_once __DIR__ . "/db.php";

$termino = $_GET['q'] ?? '';
$termino = trim($termino);

$canciones = [];
$playlists = [];

if ($termino !== '') {
    $sqlCanciones = "SELECT * FROM songs WHERE titulo LIKE :term OR artista LIKE :term OR album LIKE :term";
    $stmt = $pdo->prepare($sqlCanciones);
    $stmt->execute(['term' => "%$termino%"]);
    $canciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sqlPlaylists = "SELECT * FROM playlists WHERE nombre LIKE :term OR descripcion LIKE :term";
    $stmt = $pdo->prepare($sqlPlaylists);
    $stmt->execute(['term' => "%$termino%"]);
    $playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
