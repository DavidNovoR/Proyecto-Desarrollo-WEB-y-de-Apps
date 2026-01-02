const audioPlayer = document.querySelector('.audio-player');
const playerCover = document.querySelector('.player-cover');
const playerTitle = document.querySelector('.player-info h4');
const playerArtist = document.querySelector('.player-info p');
const playBtn = document.querySelector('.player-play');
const playIcon = document.querySelector('.player-play-icon');
const nextBtn = document.querySelector('.player-next');
const prevBtn = document.querySelector('.player-prev');
let currentSongId = null;

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
    currentSongId = card.dataset.songId;


    playerCover.src = cover;
    playerTitle.textContent = title;
    playerArtist.textContent = artist;

    playerAudio.src = audio;
    playerAudio.load();

    playerAudio.addEventListener('canplay', () => {
      playerAudio.play();
      audioPlayer.classList.add('active');
      playIcon.src = "../../Frontend/img/icons/pause_circle_icon.png";
      registrarReproduccion(currentSongId);

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
  currentSongId = song.id;
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
    registrarReproduccion(currentSongId);
  }, { once: true });
}
