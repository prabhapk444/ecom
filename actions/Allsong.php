<?php
require("db.php");

$sql = "SELECT * FROM songs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $songsByCategory = [];
    $allSongs = []; // Array to store all songs

    while ($row = $result->fetch_assoc()) {
        $allSongs[] = $row; // Store all songs
        $songsByCategory[$row['category']][] = $row; // Group songs by category
    }

    echo '<div class="song-container">';

    // Display all songs first
    echo '<h3 class="category-title">All Songs</h3>';
    echo '<div class="song-grid">';
    foreach ($allSongs as $song) {
        $songTitle = htmlspecialchars($song['title']);
        $songAuthor = htmlspecialchars($song['author']);
        $songPath = 'Admin/' . htmlspecialchars($song['song_path']);
        $songImagePath = 'Admin/' . htmlspecialchars($song['image_path']);

        echo '
        <div class="song-card" data-aos="zoom-in" data-aos-duration="800">
            <div class="song-img">
                <img src="' . $songImagePath . '" alt="Song Image">
            </div>
            <div class="song-info">
                <h2>Song: ' . $songTitle . '</h2>
                <p>Author: ' . $songAuthor . '</p>
            </div>
            <audio id="audio-' . $song['id'] . '" src="' . $songPath . '"></audio>
            <div class="song-controls">
                <button class="play-btn" onclick="playSong(' . $song['id'] . ')">
                    <i class="fas fa-play play-icon"></i>
                </button>
                <button class="stop-btn" onclick="stopSong(' . $song['id'] . ')">
                    <i class="fas fa-stop stop-icon"></i>
                </button>
                <a href="' . $songPath . '" download class="download-btn">
                    <i class="fas fa-download download-icon"></i>
                </a>
                <button class="share-btn" onclick="shareSong(\'' . $songTitle . '\', \'' . $songAuthor . '\', \'' . $songPath . '\')">
                    <i class="fas fa-share share-icon"></i>
                </button>
                <button class="like-btn" id="like-btn-' . $song['id'] . '" onclick="toggleLike(' . $song['id'] . ')">
                    <i class="fas fa-thumbs-up like-icon"></i>
                </button>
            </div>
        </div>';
    }
    echo '</div>'; // Close song-grid

    // Display songs by category
    foreach ($songsByCategory as $category => $songs) {
        echo '<h3 class="category-title">' . htmlspecialchars($category) . '</h3>';
        echo '<div class="song-grid">';

        foreach ($songs as $song) {
            $songTitle = htmlspecialchars($song['title']);
            $songAuthor = htmlspecialchars($song['author']);
            $songPath = 'Admin/' . htmlspecialchars($song['song_path']);
            $songImagePath = 'Admin/' . htmlspecialchars($song['image_path']);

            echo '
            <div class="song-card" data-aos="zoom-in" data-aos-duration="800">
                <div class="song-img">
                    <img src="' . $songImagePath . '" alt="Song Image">
                </div>
                <div class="song-info">
                    <h2>Song: ' . $songTitle . '</h2>
                    <p>Author: ' . $songAuthor . '</p>
                </div>
                <audio id="audio-' . $song['id'] . '" src="' . $songPath . '"></audio>
                <div class="song-controls">
                    <button class="play-btn" onclick="playSong(' . $song['id'] . ')">
                        <i class="fas fa-play play-icon"></i>
                    </button>
                    <button class="stop-btn" onclick="stopSong(' . $song['id'] . ')">
                        <i class="fas fa-stop stop-icon"></i>
                    </button>
                    <a href="' . $songPath . '" download class="download-btn">
                        <i class="fas fa-download download-icon"></i>
                    </a>
                    <button class="share-btn" onclick="shareSong(\'' . $songTitle . '\', \'' . $songAuthor . '\', \'' . $songPath . '\')">
                        <i class="fas fa-share share-icon"></i>
                    </button>
                    <button class="like-btn" id="like-btn-' . $song['id'] . '" onclick="toggleLike(' . $song['id'] . ')">
                        <i class="fas fa-thumbs-up like-icon"></i>
                    </button>
                </div>
            </div>';
        }
        echo '</div>'; // Close song-grid
    }

    echo '</div>'; // Close song-container
} else {
    echo "<p>No songs available.</p>";
}

$conn->close();
?>

<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

<script>
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

    window.onload = function() {
        resumeSong();
    };

    function stopSong(id) {
        const audio = document.getElementById('audio-' + id);
        audio.pause();
        sessionStorage.removeItem('currentSongId');
        sessionStorage.removeItem('audioSrc');
    }

    function toggleLike(songId) {
        const likeButton = document.getElementById('like-btn-' + songId);
        likeButton.classList.toggle('liked');
        // Optional: Handle the logic for liking the song (e.g., send to server)
    }
</script>

<style>
    * {
        font-family: 'Roboto', sans-serif;
    }

    .song-container {
        padding: 20px;
        background-color: #121212;
    }

    .category-title {
        color: #ffffff;
        font-size: 24px;
        margin: 20px 0 10px;
        text-transform: uppercase;
    }

    .song-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
    }

    .song-card {
        background-color: #181818;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        width: calc(20% - 20px);
        margin: 10px 0;
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .song-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
    }

    .song-img img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
    }

    .song-info {
        text-align: center;
        margin-top: 10px;
    }

    .song-info h2 {
        font-size: 16px;
        margin-bottom: 5px;
        color: #fff;
    }

    .song-info p {
        font-size: 14px;
        color: #b3b3b3;
    }

    .song-controls {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }

    .play-btn,
    .stop-btn,
    .download-btn,
    .share-btn,
    .like-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #fff;
        font-size: 20px;
        transition: transform 0.2s;
    }

    .play-btn:hover,
    .stop-btn:hover,
    .download-btn:hover,
    .share-btn:hover,
    .like-btn:hover {
        transform: scale(1.1);
    }

    /* Icon Colors */
    .download-icon {
        color: #007bff; /* Blue for download button */
    }

    .share-icon {
        color: #fcb900; /* Yellow for share button */
    }

    .like-icon {
        color: #d32f2f; /* Red for like button */
    }

    .like-btn.liked .like-icon {
        color: #ff4081; /* Pink when liked */
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .song-card {
            width: calc(50% - 20px); /* 2 cards per row */
        }
    }

    @media (max-width: 600px) {
        .song-card {
            width: 100%; /* 1 card per row */
        }
    }
</style>
