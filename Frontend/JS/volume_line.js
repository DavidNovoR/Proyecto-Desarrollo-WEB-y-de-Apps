const volumeBar = document.getElementById('volume-bar'); 
// Actualizar volumen cuando se mueve el slider 
volumeBar.addEventListener('input', () => { 
    const volumeValue = volumeBar.value / 100; // Convertir 0–100 a 0.0–1.0 
    playerAudio.volume = volumeValue; 
});