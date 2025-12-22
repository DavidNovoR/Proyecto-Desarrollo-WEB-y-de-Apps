const progressBar = document.getElementById('progress-bar');
const currentTimeLabel = document.getElementById('current-time');
const totalTimeLabel = document.getElementById('total-time');

// Actualizar duración total cuando se carga una nueva canción
playerAudio.addEventListener('loadedmetadata', () => {
  const duration = playerAudio.duration;
  progressBar.max = Math.floor(duration);
  totalTimeLabel.textContent = formatTime(duration);
});

// Actualizar barra y tiempo actual mientras suena
playerAudio.addEventListener('timeupdate', () => {
  progressBar.value = Math.floor(playerAudio.currentTime);
  currentTimeLabel.textContent = formatTime(playerAudio.currentTime);
});

// Permitir que el usuario adelante/retroceda
progressBar.addEventListener('input', () => {
  playerAudio.currentTime = progressBar.value;
});

// Formatear segundos a mm:ss
function formatTime(seconds) {
  const min = Math.floor(seconds / 60);
  const sec = Math.floor(seconds % 60);
  return `${min}:${sec < 10 ? '0' + sec : sec}`;
}
