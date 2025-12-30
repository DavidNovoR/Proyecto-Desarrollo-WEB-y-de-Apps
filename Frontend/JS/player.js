const audioPlayer = document.querySelector('.audio-player');
const playerCover = document.querySelector('.player-cover');
const playerTitle = document.querySelector('.player-info h4');
const playerArtist = document.querySelector('.player-info p');
const playBtn = document.querySelector('.player-play');
const playIcon = document.querySelector('.player-play-icon');
const nextBtn = document.querySelector('.player-next');
const prevBtn = document.querySelector('.player-prev');

const playerAudio = new Audio();

let currentIndex = -1;

// CLICK EN UNA CANCIÓN DE LA LISTA
document.querySelectorAll('.song-card-large').forEach((card, index) => {
  card.addEventListener('click', () => {

    currentIndex = index;

    const cover  = card.querySelector('.portada img').src;
    const title  = card.querySelector('.titulo h3').textContent;
    const artist = card.querySelector('.autor p').textContent;
    const audio  = card.dataset.audio;

    playerCover.src = cover;
    playerTitle.textContent = title;
    playerArtist.textContent = artist;

    playerAudio.src = audio;
    playerAudio.load();

    playerAudio.addEventListener('canplay', () => {
      playerAudio.play();
      audioPlayer.classList.add('active');
      playIcon.src = "../../Frontend/img/icons/pause_circle_icon.png";
    }, { once: true });
  });
});

// PLAY / PAUSE
playBtn.addEventListener('click', () => {
  if (playerAudio.paused) {
    playerAudio.play();
    playIcon.src = "../../Frontend/img/icons/pause_circle_icon.png";
  } else {
    playerAudio.pause();
    playIcon.src = "../../Frontend/img/icons/play_circle_icon.png";
  }
});

// CUANDO TERMINA → SUMAR 1 Y PASAR A LA SIGUIENTE
playerAudio.addEventListener('ended', () => {

  // 🔥 SUMAR REPRODUCCIÓN SOLO AL TERMINAR
  if (currentIndex !== -1) {
    fetch("../../Backend/PHP/increment_reproducciones.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + encodeURIComponent(songList[currentIndex].id)
    });
  }

  // Pasar a la siguiente canción
  currentIndex = (currentIndex + 1) % songList.length;
  loadSong(currentIndex);
});

// SIGUIENTE CANCIÓN
nextBtn.addEventListener('click', () => {
  if (currentIndex === -1) return;

  currentIndex = (currentIndex + 1) % songList.length;
  loadSong(currentIndex);
});

// CANCIÓN ANTERIOR
prevBtn.addEventListener('click', () => {
  if (currentIndex === -1) return;

  currentIndex = (currentIndex - 1 + songList.length) % songList.length;
  loadSong(currentIndex);
});

// CARGAR CANCIÓN POR ÍNDICE (NO SUMA REPRODUCCIONES)
function loadSong(index) {
  const song = songList[index];
  if (!song) return;

  currentIndex = index;

  playerCover.src = song.portada;
  playerTitle.textContent = song.titulo;
  playerArtist.textContent = song.artista + " • " + (song.album ?? "Sin álbum");

  playerAudio.src = song.audio_url;
  playerAudio.load();

  playerAudio.addEventListener('canplay', () => {
    playerAudio.play();
    audioPlayer.classList.add('active');
    playIcon.src = "../../Frontend/img/icons/pause_circle_icon.png";
  }, { once: true });
}
