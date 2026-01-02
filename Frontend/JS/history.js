function registrarReproduccion(songId) {
    if (!songId) return;

    fetch("../../Backend/PHP/add_history.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "song_id=" + encodeURIComponent(songId)
    }).catch(() => {
        console.error("Error registrando reproducción");
    });
}

window.registrarReproduccion = registrarReproduccion;
