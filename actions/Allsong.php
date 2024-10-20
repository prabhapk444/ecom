<?php
require("db.php");

$sql = "SELECT * FROM songs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="song-grid">';
    $songIndex = 0; // Index to track the order of songs
    while ($row = $result->fetch_assoc()) {
        $songTitle = $row['title'];
        $songAuthor = $row['author'];
        $songPath = 'Admin/' . $row['song_path'];
        $songImagePath = 'Admin/' . $row['image_path'];
        
        echo '
        <div class="song-card" data-aos="zoom-in" data-aos-duration="800">
            <div class="song-img">
                <img src="' . $songImagePath . '" alt="Song Image">
            </div>
            <div class="song-info">
                <h2>Song: ' . $songTitle . '</h2>
                <p>Author: ' . $songAuthor . '</p>
            </div>
            <audio id="audio-' . $songIndex . '" src="' . $songPath . '"></audio>
            <div class="song-controls">
                <button class="play-btn" onclick="playSong(' . $songIndex . ')"><i class="fas fa-play"></i></button>
                <button class="stop-btn" onclick="stopSong(' . $songIndex . ')"><i class="fas fa-stop"></i></button>
                <a href="' . $songPath . '" download class="download-btn"><i class="fas fa-download"></i></a>
                <button class="share-btn" onclick="shareSong(\'' . $songTitle . '\', \'' . $songAuthor . '\', \'' . $songPath . '\')"><i class="fas fa-share"></i></button>
            </div>
        </div>';
        $songIndex++;
    }
    echo '</div>';
} else {
    echo "<p>No songs available.</p>";
}

$conn->close();
?>


 <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
 <script>
    let currentSongIndex = 0;
    const totalSongs = <?php echo $songIndex; ?>; 
    function playSong(index) {
        stopAllSongs();
        currentSongIndex = index;
        const currentAudio = document.getElementById(`audio-${index}`);
        currentAudio.play();
        currentAudio.onended = playNextSong;
    }

    function stopSong(index) {
        const currentAudio = document.getElementById(`audio-${index}`);
        currentAudio.pause();
        currentAudio.currentTime = 0; 
    }

    function stopAllSongs() {
        for (let i = 0; i < totalSongs; i++) {
            stopSong(i);
        }
    }

    function playNextSong() {
        currentSongIndex++;
        if (currentSongIndex < totalSongs) {
            playSong(currentSongIndex);
        }
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

</script>


<style>
     * {
            font-family: 'Roboto', sans-serif;
        }
    .song-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
    }

   
    .song-card {
        background-color: #181818;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        width: calc(20% - 20px); /
        margin: 10px 0;
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: transform 0.3s ease;
        box-sizing: border-box; 
    }

    .song-card:hover {
        transform: scale(1.05);
    }

    .song-img img {
        width: 100%; 
        height: 150px;
        object-fit: cover; 
        border-radius: 15px;
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
        font-size: 12px;
        color: #b3b3b3;
    }

    .song-controls {
        display: flex;
        justify-content: space-around;
        align-items: center;
        width: 100%;
        margin-top: 10px;
    }

    button, .download-btn, .share-btn {
        background-color: #1db954;
        border: none;
        color: white;
        border-radius: 50%;
        padding: 8px;
        cursor: pointer;
        text-align: center;
        font-size: 14px;
    }

    button i, .download-btn i, .share-btn i {
        font-size: 16px;
    }

    button:hover, .download-btn:hover, .share-btn:hover {
        background-color: #1ed760;
    }

    .download-btn {
        background-color: #404040;
    }

    .download-btn:hover {
        background-color: #505050;
    }

    .share-btn {
        background-color: #3b5998; 
    }

    .share-btn:hover {
        background-color: #4c70ba;
    }

    audio {
        display: none;
    }

    @media screen and (max-width: 992px) {
        .song-grid {
            justify-content: center;
        }

        .song-card {
            width: 90%;
        }
    }
</style>
