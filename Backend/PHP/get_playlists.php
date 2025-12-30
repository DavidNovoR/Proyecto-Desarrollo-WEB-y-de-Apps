<?php
require_once(__DIR__ . '/db.php');

try {
    // Obtener todas las playlists ordenadas por nombre
    $stmt = $pdo->query("SELECT id, nombre, descripcion, imagen FROM playlists ORDER BY nombre ASC");
    $playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        "playlists" => $playlists
    ];

} catch (PDOException $e) {
    die("Error al obtener playlists: " . $e->getMessage());
}
