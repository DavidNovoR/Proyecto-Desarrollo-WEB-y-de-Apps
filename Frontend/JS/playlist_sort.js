document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("playlist-container");
    if (!container) return;

    let dragged = null;

    container.addEventListener("dragstart", (e) => {
        dragged = e.target.closest(".song-card-large");
        if (!dragged) return;

        e.dataTransfer.effectAllowed = "move";

        const img = document.createElement("img");
        img.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAE0lEQVR42mP8z8BQz0AEYBxVSF0AAGv7CEKd4H0AAAAASUVORK5CYII=";
        img.width = 0;
        img.height = 0;
        e.dataTransfer.setDragImage(img, 0, 0);

        dragged.classList.add("dragging");
    });

    container.addEventListener("dragover", (e) => {
        e.preventDefault();
        const target = e.target.closest(".song-card-large");
        if (!target || target === dragged) return;

        const rect = target.getBoundingClientRect();
        const next = (e.clientY - rect.top) / rect.height > 0.5;
        container.insertBefore(dragged, next ? target.nextSibling : target);
    });

    container.addEventListener("dragend", () => {
        if (dragged) dragged.classList.remove("dragging");
        dragged = null;
        saveOrder();
    });

    function saveOrder() {
        const order = [...container.querySelectorAll(".song-card-large")].map((el, index) => ({
            id: el.dataset.id,
            orden: index + 1
        }));

        fetch("update_playlist_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ playlist_id: PLAYLIST_ID, order })
        })
            .then(res => res.json())
            .then(data => {
                if (data.status !== "success") {
                    console.error("Error guardando el orden:", data.message);
                }
            })
            .catch(err => console.error("Error guardando el orden:", err));
    }
});
