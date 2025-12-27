<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Acceso no permitido");
}

$nombre    = trim($_POST["nombre"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$usuario   = trim($_POST["user"] ?? "");
$email     = trim($_POST["email"] ?? "");
$password  = $_POST["password"] ?? "";

if (
    $nombre === "" ||
    $apellidos === "" ||
    $usuario === "" ||
    $email === "" ||
    $password === ""
) {
    exit("Todos los campos son obligatorios");
}

$sql = "SELECT id FROM Users
        WHERE usuarios = :usuario OR email = :email
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "usuario" => $usuario,
    "email"   => $email
]);

if ($stmt->fetch()) {
    exit("El usuario o el email ya están registrados");
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users
        (nombre, apellidos, usuarios, email, password, rol)
        VALUES
        (:nombre, :apellidos, :usuario, :email, :password, :rol)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "nombre"    => $nombre,
    "apellidos" => $apellidos,
    "usuario"   => $usuario,
    "email"     => $email,
    "password"  => $passwordHash,
    "rol"       => "user"
]);

header("Location: login.html");
exit;
