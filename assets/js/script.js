let currentAudio = null;
    let currentSongId = null;

    function playSong(songId) {
        if (currentAudio && currentAudio !== document.getElementById('audio-' + songId)) {
            currentAudio.pause();
        }

        currentAudio = document.getElementById('audio-' + songId);
        currentAudio.play();
        currentSongId = songId;
        const songItem = document.querySelector('.song-item[data-id="' + songId + '"]');
        const songTitle = songItem.getAttribute('data-title');
        const songAuthor = songItem.getAttribute('data-author');
        const songImage = songItem.getAttribute('data-image');

        document.getElementById('currentSongTitle').innerText = songTitle;
        document.getElementById('currentSongAuthor').innerText = songAuthor;
        const currentSongImage = document.getElementById('currentSongImage');
        currentSongImage.src = songImage;
        currentSongImage.style.display = 'block';
        document.getElementById('playPauseButton').innerHTML = '<i class="fas fa-pause"></i>';

        updateProgressBar();
    }

    function updateProgressBar() {
        if (currentAudio) {
            const progressBar = document.getElementById('progressBar');
            currentAudio.ontimeupdate = function () {
                progressBar.value = (currentAudio.currentTime / currentAudio.duration) * 100;
                document.getElementById('currentTime').innerText = formatTime(currentAudio.currentTime);
            };
            currentAudio.onended = playNextSong; 
        }
    }

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    function seekSong(event) {
        if (currentAudio) {
            const seekTime = (event.target.value / 100) * currentAudio.duration;
            currentAudio.currentTime = seekTime;
        }
    }

    function playNextSong() {
        const songItems = Array.from(document.querySelectorAll('.song-item'));
        const currentIndex = songItems.findIndex(item => item.getAttribute('data-id') == currentSongId);
        const nextIndex = (currentIndex + 1) % songItems.length;
        const nextSongItem = songItems[nextIndex];

        playSong(parseInt(nextSongItem.getAttribute('data-id')));
    }

    function playPreviousSong() {
        const songItems = Array.from(document.querySelectorAll('.song-item'));
        const currentIndex = songItems.findIndex(item => item.getAttribute('data-id') == currentSongId);
        const previousSongItem = songItems[currentIndex - 1];

        if (previousSongItem) {
            playSong(parseInt(previousSongItem.getAttribute('data-id')));
        }
    }

    function togglePlayPause() {
        if (currentAudio) {
            if (currentAudio.paused) {
                currentAudio.play();
                document.getElementById('playPauseButton').innerHTML = '<i class="fas fa-pause"></i>';
            } else {
                currentAudio.pause();
                document.getElementById('playPauseButton').innerHTML = '<i class="fas fa-play"></i>';
            }
        }
    }

    function resetSongInfo() {
        currentSongId = null;
        currentAudio = null;
        document.getElementById('currentSongTitle').innerText = 'No song playing';
        document.getElementById('currentSongAuthor').innerText = '';
        const currentSongImage = document.getElementById('currentSongImage');
        currentSongImage.src = '';
        currentSongImage.style.display = 'none';
        document.getElementById('playPauseButton').innerHTML = '<i class="fas fa-play"></i>';
    }

    
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
