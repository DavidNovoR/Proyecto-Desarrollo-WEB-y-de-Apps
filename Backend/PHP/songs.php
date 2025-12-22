<?php
require_once __DIR__ . "/db.php";

$stmt = $pdo->query("SELECT * FROM songs LIMIT 10");
$canciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
