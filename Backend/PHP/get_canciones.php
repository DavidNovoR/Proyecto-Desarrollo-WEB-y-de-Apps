<?php
require_once __DIR__ . "/db.php";
header("Content-Type: application/json");

try {
    $sql = "SELECT id, titulo FROM songs ORDER BY titulo ASC";
    $stmt = $pdo->query($sql);
    $canciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($canciones);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al obtener canciones", "detalle" => $e->getMessage()]);
}
