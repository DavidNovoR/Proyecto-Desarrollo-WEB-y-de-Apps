document.querySelectorAll('.like-bt').forEach(btn => {
  btn.addEventListener('click', () => {
    const img = btn.querySelector('img');
    const songId = btn.dataset.song;
    const isLiked = img.src.includes("like-red.png");

    // Toggle imagen
    img.src = isLiked
      ? "../../Frontend/img/icons/like.png"
      : "../../Frontend/img/icons/like-red.png";

    // Acción para BD
    const action = isLiked ? "remove" : "add";

    // AJAX
    fetch("../../Backend/PHP/like.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `song_id=${songId}&action=${action}`
    })
    .then(res => res.text())
    .then(msg => console.log("Favorito:", msg))
    .catch(err => console.error("Error:", err));
  });
});
