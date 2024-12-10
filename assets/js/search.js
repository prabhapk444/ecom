let recognition;




function startVoiceSearch() {
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.lang = 'en-US';
        recognition.interimResults = false; 
        recognition.maxAlternatives = 1;
        recognition.onstart = function () {
            console.log("Voice recognition started. Speak into the microphone.");
        };


        recognition.onresult = function (event) {
            const transcript = event.results[0][0].transcript.trim().toLowerCase();
            console.log('Voice input:', transcript);
        
            if (transcript.includes('stop music') || transcript.includes('stop the music')) {
                console.log("Stop song command recognized");
                stopSong(); 
            }
             else {
                document.getElementById('searchBar').value = transcript;
                searchSongs();
                playFirstMatch();
            }
            recognition.stop();
        };
        
        
        

        recognition.onerror = function (event) {
            console.error('Speech recognition error:', event.error);
            alert('Error recognizing speech. Please try again.');
            recognition.stop();
        };

        recognition.onspeechend = function () {
            console.log("Voice recognition ended.");
            recognition.stop();
        };

        recognition.start();
    } else {
        alert('Your browser does not support voice recognition. Please use a modern browser.');
    }
}

function searchSongs() {
    const input = document.getElementById('searchBar').value.toLowerCase();
    const songItems = document.querySelectorAll('.song-item');
    
    songItems.forEach(item => {
        const title = item.getAttribute('data-title').toLowerCase();
        const author = item.getAttribute('data-author').toLowerCase();

        if (title.includes(input) || author.includes(input)) {
            item.style.display = ""; 
        } else {
            item.style.display = "none";
        }
    });
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
    const audioElement = document.getElementById('audio-' + id);
    document.querySelectorAll('audio').forEach(audio => {
        if (audio !== audioElement) {
            audio.pause();
            audio.currentTime = 0;
        }
    });
    audioElement.play();
}


function stopSong(id) {
    const audioElement = document.getElementById('audio-' + id);
    if (audioElement) {
        audioElement.pause();
        audioElement.currentTime = 0;
        console.log('Audio stopped successfully.');
    } else {
        console.error('Audio element with ID ' + id + ' not found.');
        alert('Audio element not found.');
    }
}






function playNextSong(id) {
    const currentAudio = document.querySelector('audio:not([paused])');
    if (currentAudio) {
        let nextAudio = currentAudio.parentElement.nextElementSibling;
        while (nextAudio) {
            if (nextAudio.tagName === 'LI' && nextAudio.querySelector('audio')) {
                const nextAudioElement = nextAudio.querySelector('audio');
                currentAudio.pause();
                currentAudio.currentTime = 0;
                nextAudioElement.play();
                console.log("Playing next song.");
                return;
            }
            nextAudio = nextAudio.nextElementSibling;
        }
        console.log("No next song available.");
        alert("No next song available.");
    } else {
        console.log("No song is currently playing.");
        alert("No song is currently playing.");
    }
}


