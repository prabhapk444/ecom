window.onload = function() {
           
    resumeSong();
};


function shareSong(title, author, songPath) {
if (navigator.share) {
    navigator.share({
        title: `Listen to ${title} by ${author}`,
        text: `Check out this song: ${title} by ${author}`,
        url: window.location.origin + '/' + songPath
    }).then(() => {
        console.log('Song shared successfully!');
    }).catch((error) => {
        console.error('Error sharing song:', error);
    });
} else {
    alert('Your browser does not support the Web Share API. You can manually share the song link: ' + window.location.origin + '/' + songPath);
}
}

let currentAudio = null;

function saveAudioState(audio, id) {
sessionStorage.setItem('currentSongId', id);
sessionStorage.setItem('audioSrc', audio.src);
}

function playSong(id) {
const audio = document.getElementById('audio-' + id);

if (currentAudio && currentAudio.id !== 'audio-' + id) {
    currentAudio.pause();  
}

if (audio.paused) {
    audio.play();  
}

currentAudio = audio;

saveAudioState(audio, id);

audio.onended = function() {
    const nextSong = document.querySelector(`#audio-${id + 1}`);
    if (nextSong) {
        playSong(id + 1);
    }
};
}

function resumeSong() {
const savedSongId = sessionStorage.getItem('currentSongId');
const savedAudioSrc = sessionStorage.getItem('audioSrc');
if (savedSongId && savedAudioSrc) {
    const audio = document.getElementById('audio-' + savedSongId);
    if (audio && savedAudioSrc === audio.src) {
        audio.play();
        currentAudio = audio;
    }
}
}



function stopSong(id) {
const audio = document.getElementById('audio-' + id);
audio.pause();
sessionStorage.removeItem('currentSongId');
sessionStorage.removeItem('audioSrc');
}


function toggleSidebar() {
const sidebar = document.getElementById('sidebar');
sidebar.classList.toggle('active');
}
