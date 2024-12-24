let recognition;
let isPlaying = false;
let currentPlayingId = null;

function startVoiceSearch() {
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.lang = 'en-US';
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        recognition.onstart = function() {
            console.log("Voice recognition started. Speak into the microphone.");
            document.getElementById('voiceSearchBtn').style.color = '#ff0000';
        };

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript.trim().toLowerCase();
            console.log('Voice input:', transcript);
            if (transcript.includes('play') && !transcript.includes('next') && !transcript.includes('previous')) {
                const searchTerm = transcript.replace('play', '').trim();
                document.getElementById('searchBar').value = searchTerm;
                searchSongs();
                playFirstMatch();
            } 
            else if (transcript.includes('stop') || transcript.includes('pause')) {
                stopCurrentSong();
            }
            else if (transcript.includes('next') || transcript.includes('play next')) {
                playNextSong();
            }
            else if (transcript.includes('previous') || transcript.includes('play previous')) {
                playPreviousSong();
            }
            else if (transcript.includes('search')) {
                const searchTerm = transcript.replace('search', '').trim();
                document.getElementById('searchBar').value = searchTerm;
                searchSongs();
            }
            else {
                document.getElementById('searchBar').value = transcript;
                searchSongs();
            }
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error:', event.error);
            alert('Error recognizing speech. Please try again.');
            document.getElementById('voiceSearchBtn').style.color = '#000';
            recognition.stop();
        };

        recognition.onend = function() {
            console.log("Voice recognition ended.");
            document.getElementById('voiceSearchBtn').style.color = '#000';
        };

        recognition.start();
    } else {
        alert('Your browser does not support voice recognition. Please use a modern browser.');
    }
}

function searchSongs() {
    const input = document.getElementById('searchBar').value.toLowerCase();
    const songItems = document.querySelectorAll('.song-item');
    let found = false;
    
    songItems.forEach(item => {
        const title = item.getAttribute('data-title').toLowerCase();
        const author = item.getAttribute('data-author').toLowerCase();

        if (title.includes(input) || author.includes(input)) {
            item.style.display = "";
            found = true;
        } else {
            item.style.display = "none";
        }
    });

    if (!found) {
        console.log("No songs found matching the search criteria.");
    }
    
    return found;
}

function playFirstMatch() {
    const firstVisibleSong = document.querySelector('.song-item:not([style*="display: none"])');
    if (firstVisibleSong) {
        const songId = firstVisibleSong.getAttribute('data-id');
        playSong(songId);
    } else {
        console.log("No matching song found.");
        alert("No matching song found. Please try again.");
    }
}

function playSong(id) {
    stopCurrentSong(); 
    const audioElement = document.getElementById('audio-' + id);
    if (audioElement) {
        audioElement.play()
            .then(() => {
                isPlaying = true;
                currentPlayingId = id;
                updatePlayButton(id, true);
                console.log('Now playing song ID:', id);
            })
            .catch(error => {
                console.error('Error playing audio:', error);
                alert('Error playing the song. Please try again.');
            });
    }
}

function stopCurrentSong() {
    if (currentPlayingId) {
        const audioElement = document.getElementById('audio-' + currentPlayingId);
        if (audioElement) {
            audioElement.pause();
            audioElement.currentTime = 0;
            updatePlayButton(currentPlayingId, false);
        }
        isPlaying = false;
        console.log('Stopped playing song ID:', currentPlayingId);
    }
}

function updatePlayButton(id, playing) {
    const playBtn = document.querySelector(`[data-id="${id}"] .play-btn i`);
    if (playBtn) {
        playBtn.className = playing ? 'fas fa-pause' : 'fas fa-play';
    }
}

function playNextSong() {
    if (!currentPlayingId) return;
    
    const currentSong = document.querySelector(`[data-id="${currentPlayingId}"]`);
    if (!currentSong) return;

    let nextSong = currentSong.nextElementSibling;
    while (nextSong && nextSong.style.display === 'none') {
        nextSong = nextSong.nextElementSibling;
    }

    if (nextSong) {
        const nextId = nextSong.getAttribute('data-id');
        playSong(nextId);
    } else {
        console.log("No next song available");
    }
}

function playPreviousSong() {
    if (!currentPlayingId) return;
    
    const currentSong = document.querySelector(`[data-id="${currentPlayingId}"]`);
    if (!currentSong) return;

    let prevSong = currentSong.previousElementSibling;
    while (prevSong && prevSong.style.display === 'none') {
        prevSong = prevSong.previousElementSibling;
    }

    if (prevSong) {
        const prevId = prevSong.getAttribute('data-id');
        playSong(prevId);
    } else {
        console.log("No previous song available");
    }
}


document.querySelectorAll('audio').forEach(audio => {
    audio.addEventListener('ended', function() {
        const songId = this.id.replace('audio-', '');
        updatePlayButton(songId, false);
        playNextSong();
    });
});