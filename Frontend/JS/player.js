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
    playerAudio.play();
    audioPlayer.classList.add('active');

    playIcon.src = "../../Frontend/img/icons/pause_circle_icon.png";
  });
});

playBtn.addEventListener('click', () => {
  if (playerAudio.paused) {
    playerAudio.play();
    playIcon.src = "../../Frontend/img/icons/pause_circle_icon.png";
  } else {
    playerAudio.pause();
    playIcon.src = "../../Frontend/img/icons/play_circle_icon.png";
  }
});

playerAudio.addEventListener('ended', () => {
  playIcon.src = "../../Frontend/img/icons/play_circle_icon.png";
});

nextBtn.addEventListener('click', () => {
    if (currentIndex < songList.length - 1) {
        loadSong(currentIndex + 1); // siguiente
    } else {
        // si ya no quedan más → aleatoria
        const randomIndex = Math.floor(Math.random() * songList.length);
        loadSong(randomIndex);
    }
});

prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        // Ir a la canción anterior 
        currentIndex--; 
    } else { 
        // Si estamos en la primera → aleatoria 
        currentIndex = Math.floor(Math.random() * songList.length); 
    } 
    loadSong(currentIndex); 
});

function loadSong(index) {
    const song = songList[index];
    if (!song) return;

    currentIndex = index;

    playerCover.src = song.portada;
    playerTitle.textContent = song.titulo;
    playerArtist.textContent = song.artista + " • " + (song.album ?? "Sin álbum");

    playerAudio.src = song.audio_url;
    playerAudio.play();
    audioPlayer.classList.add('active');

    playIcon.src = "../../Frontend/img/icons/pause_circle_icon.png";
}
