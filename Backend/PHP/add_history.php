<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit;
}

$song_id = $_POST["song_id"] ?? null;
$user_id = $_SESSION["user_id"];

if (!$song_id) {
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO history (user_id, song_id) VALUES (?, ?)"
);
$stmt->execute([$user_id, $song_id]);

$stmt = $pdo->prepare(
    "UPDATE songs
     SET reproducciones = reproducciones + 1
     WHERE id = ?"
);
$stmt->execute([$song_id]);

echo json_encode(["status" => "ok"]);
