<?php
require "/db.php";

$API_KEY = "4690e24f";

$url = "https://api.jamendo.com/v3.0/tracks?" . http_build_query([
    "client_id" => $API_KEY,
    "format" => "json",
    "limit" => 100,
    "include" => "musicinfo",
    "audioformat" => "mp32",
    "license_cc" => "ccby,ccby-sa"
]);

$json = file_get_contents($url);
$data = json_decode($json, true);

if (!isset($data["results"])) {
    die("Error obteniendo canciones");
}

foreach ($data["results"] as $track) {

    // evitar duplicados
    $check = $pdo->prepare("SELECT id FROM songs WHERE api_id = ?");
    $check->execute([$track["id"]]);

    if ($check->rowCount() > 0) {
        continue;
    }

    $duracion = gmdate("i:s", $track["duration"]);

    $stmt = $pdo->prepare("
        INSERT INTO songs
        (api_id, titulo, artista, album, duracion, portada, audio_url, genero, licencia)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $track["id"],
        $track["name"],
        $track["artist_name"],
        $track["album_name"] ?? null,
        $duracion,
        $track["image"],
        $track["audio"],
        $track["musicinfo"]["tags"]["genres"][0] ?? "Unknown",
        $track["license_cc"] ?? "Desconocida"
    ]);
}

echo "Canciones importadas correctamente";
