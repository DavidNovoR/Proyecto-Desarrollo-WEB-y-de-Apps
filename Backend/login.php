<?php
session_start();
require_once "conexion.php"; // aquí va TU archivo PDO

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Acceso no permitido");
}

$emailOrUser = trim($_POST["email"] ?? "");
$password    = $_POST["password"] ?? "";

if ($emailOrUser === "" || $password === "") {
    exit("Campos vacíos");
}

$sql = "SELECT * FROM Users
        WHERE email = :login OR usuarios = :login
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "login" => $emailOrUser
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit("Usuario o contraseña incorrectos");
}

if ($password !== $user["password"])
    exit("Usuario o contraseña incorrectos");
}

$_SESSION["user_id"] = $user["id"];
$_SESSION["usuario"] = $user["usuarios"];
$_SESSION["rol"] = $user["rol"];

header("Location: dashboard.php");
exit;
