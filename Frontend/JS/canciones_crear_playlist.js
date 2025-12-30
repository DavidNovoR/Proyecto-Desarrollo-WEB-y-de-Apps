// ===============================
// 1. Cargar canciones desde la BD
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    fetch("../../Backend/PHP/get_canciones.php")
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data)) {
                console.error("Respuesta inesperada del servidor:", data);
                return;
            }

            const select = document.getElementById("canciones");

            data.forEach(cancion => {
                const option = document.createElement("option");
                option.value = cancion.id;
                option.textContent = cancion.titulo;
                select.appendChild(option);
            });
        })
        .catch(error => console.error("Error cargando canciones:", error));
});

// ==========================================
// 2. Enviar formulario y crear la playlist
// ==========================================
document.getElementById("formPlaylist").addEventListener("submit", function (e) {
    e.preventDefault(); // evita recargar la página

    const form = e.target;
    const formData = new FormData(form);

    fetch("../../Backend/PHP/crear_playlist.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Respuesta del servidor:", data);

        if (data.trim() === "ok") {
            // Redirigir a inicio.php
            window.location.href = "../../Backend/PHP/index.php";
        } else {
            alert("❌ Hubo un problema al crear la playlist");
        }
    })
    .catch(error => {
        console.error("Error al crear la playlist:", error);
        alert("❌ Error al guardar la playlist");
    });
});