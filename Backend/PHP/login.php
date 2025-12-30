<?php
session_start();
require_once __DIR__ . "/db.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($input) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Rellena todos los campos."]);
        exit;
    }

    // Buscar por email o usuario
    $sql = "SELECT id, usuario, email, password FROM users 
            WHERE email = :input OR usuario = :input LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["input" => $input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "error", "message" => "Usuario o email no encontrado."]);
        exit;
    }

    if (!password_verify($password, $user["password"])) {
        echo json_encode(["status" => "error", "message" => "Contraseña incorrecta."]);
        exit;
    }

    // Login correcto
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["usuario"] = $user["usuario"];

    echo json_encode(["status" => "success"]);
    exit;
}
?>
