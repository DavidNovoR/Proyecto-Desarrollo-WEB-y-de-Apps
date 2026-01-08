<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['canciones'])) {
    echo json_encode(["status" => "error", "message" => "No hay canciones"]);
    exit;
}

$placeholders = implode(',', array_fill(0, count($data['canciones']), '?'));

$stmt = $pdo->prepare("DELETE FROM songs WHERE id IN ($placeholders)");
$stmt->execute($data['canciones']);

echo json_encode([
    "status" => "success",
    "message" => "Canciones eliminadas correctamente"
]);
