<?php
require_once(__DIR__ . '/db.php');
$userId = $_SESSION["user_id"] ?? null;

if (!$userId) {
    return ["playlists" => []];
}

try {
    $sql = "
        SELECT id, nombre, descripcion, imagen
        FROM playlists
        WHERE 
            visibilidad = 'publica'
            OR (visibilidad = 'privada' AND user_id = :uid)
        ORDER BY nombre ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "uid" => $userId
    ]);

    return [
        "playlists" => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ];

} catch (PDOException $e) {
    die('Error al obtener playlists: ' . $e->getMessage());
}
