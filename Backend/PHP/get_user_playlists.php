<?php
require_once __DIR__ . "/db.php";

$userId = $_SESSION["user_id"] ?? null;

if (!$userId) {
    return ["playlists" => []];
}

$sql = "SELECT * FROM playlists WHERE user_id = :uid";
$stmt = $pdo->prepare($sql);
$stmt->execute(["uid" => $userId]);

return [
    "playlists" => $stmt->fetchAll(PDO::FETCH_ASSOC)
];
