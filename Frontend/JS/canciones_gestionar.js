document.addEventListener("DOMContentLoaded", function () {
    fetch("../../Backend/PHP/get_canciones.php")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("canciones");

            data.forEach(cancion => {
                const option = document.createElement("option");
                option.value = cancion.id;
                option.textContent = cancion.titulo + " — " + cancion.artista;
                select.appendChild(option);
            });
        });
});
document.getElementById("formDeleteSongs").addEventListener("submit", function (e) {
    e.preventDefault();

    const canciones = [...document.getElementById("canciones").selectedOptions]
        .map(opt => opt.value);

    if (canciones.length === 0) {
        alert("Selecciona al menos una canción");
        return;
    }

    if (!confirm("⚠¿Seguro que quieres eliminar estas canciones definitivamente?")) {
        return;
    }

    fetch("../../Backend/PHP/delete_songs.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ canciones })
    })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            location.reload();
        });
});
