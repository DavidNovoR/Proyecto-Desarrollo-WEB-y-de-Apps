<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$audioDir = __DIR__ . "/../music/"; // corregido
if (!is_dir($audioDir)) mkdir($audioDir, 0777, true);

$portadaDir = __DIR__ . "/../../Frontend/img/";
if (!is_dir($portadaDir)) mkdir($portadaDir, 0777, true);

$stmt = $pdo->query("SELECT MAX(id) AS max_id FROM songs");
$maxId = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];
$nextId = $maxId ? $maxId + 1 : 1;

if (!isset($_FILES['audio_file']) || $_FILES['audio_file']['error'] != 0) {
    die("Error al subir el archivo de audio.");
}
$audioExt = pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION);
$audioFileName = "audio_" . $nextId . "." . $audioExt;
$audioPath = $audioDir . $audioFileName;
if (!move_uploaded_file($_FILES['audio_file']['tmp_name'], $audioPath)) {
    die("No se pudo mover el archivo de audio.");
}

if (!isset($_FILES['portada_file']) || $_FILES['portada_file']['error'] != 0) {
    die("Error al subir la portada.");
}
$portadaExt = pathinfo($_FILES['portada_file']['name'], PATHINFO_EXTENSION);
$portadaFileName = "portada_" . $nextId . "." . $portadaExt;
$portadaPath = $portadaDir . $portadaFileName;
if (!move_uploaded_file($_FILES['portada_file']['tmp_name'], $portadaPath)) {
    die("No se pudo mover la portada.");
}

$stmt = $pdo->prepare("
    INSERT INTO songs
    (api_id, titulo, artista, album, duracion, portada, audio_url, genero, proposito, year, licencia, reproducciones)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    null, // api_id
    $_POST['titulo'],
    $_POST['artista'],
    $_POST['album'] ?? null,
    $_POST['duration'],
    '/../Proyecto-Desarrollo-WEB-y-de-Apps/Frontend/img/' . $portadaFileName, // portada
    '/../Proyecto-Desarrollo-WEB-y-de-Apps/Backend/music/' . $audioFileName,  // audio_url
    $_POST['genero'],
    null, // proposito
    null, // year
    $_POST['licencia'],
    0 // reproducciones
]);

header("Location: gestionar.php");
exit;
