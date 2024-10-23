<?php
session_start();  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melody Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         * {
            font-family: 'Roboto', sans-serif;
        }
body {
    display: flex;
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #121212;
    color: white;
    overflow-x: hidden;
}

.sidebar {
    width: 200px;
    background-color: #1e1e1e;
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.sidebar a {
    color: white;
    text-decoration: none;
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
    transition: background 0.3s;
}

.sidebar a:hover {
    background-color: #282828;
}

.main-content {
    flex: 1;
    padding: 20px;
    overflow-y: auto; 
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    background: linear-gradient(to bottom, #1DB954, #191414); 
    padding: 10px 20px;
    border-radius: 10px;
}

.logo {
    max-width: 120px;
    height: auto;
}

.logout-btn {
    background-color: transparent;
    border: none;
    color: white;
    cursor: pointer;
}

.spotify-button {
    text-align: center;
    background-color: #1db954;
    padding: 10px 15px;
    border: none;
    border-radius: 20px;
    color: white;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}

.controls {
    margin-top: 10px;
}

.toggle-sidebar {
    display: none;
}h3 {
    text-align: center;
    font-size: 1.5rem;
    color: #fff;
    text-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5);
    animation: shadowMove 2s infinite alternate;
}

@keyframes shadowMove {
    0% { text-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5); }
    50% { text-shadow: 10px 10px 10px rgba(255, 0, 0, 0.7); }
    100% { text-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5); }
}


@media (max-width: 992px) {
    body {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        height: auto;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 9999;
        display: none; 
        background-color: #1e1e1e;
    }

    .sidebar.active {
        display: flex; 
    }

    .main-content {
        padding: 10px;
    }

    .header {
        flex-direction: column;
        align-items: flex-start;
    }

    .logout-btn {
        width: 100%;
        margin-top: 10px;
        text-align: center;
    }

    .spotify-button {
        margin-top: 10px;
    }

    h1, p, {
        text-align: center;
    }
    

    .toggle-sidebar {
        display: block; 
        margin-bottom: 10px;
        width: 100%;
        padding: 10px;
        background-color: #1db954;
        color: white;
        font-size: 18px;
        text-align: center;
        cursor: pointer;
        border: none;
    }

 
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

    .download-icon {
        color: #007bff; 
    }

    .share-icon {
        color: #fcb900; 
    }

    .like-icon {
        color: #d32f2f; 
    }

    .like-btn.liked .like-icon {
        color: #ff4081;
    }

  
    @media (max-width: 992px) {
        .song-card {
            width: calc(50% - 20px); 
        }
    }

    @media (max-width: 600px) {
        .song-card {
            width: 100%; 
        }
    }
</style>
</head>
<body>
    <button class="toggle-sidebar" onclick="toggleSidebar()">☰</button>
    <div class="sidebar" id="sidebar">
        <a href="search.php">Search</a>
        <a href="like_song.php">Liked Songs</a>
        <a href="#">Playlists</a>
        
    </div>

    <div class="main-content">
        <div class="header">
            <img src="./assets/img/logo-Photoroom.png" alt="Logo" class="logo"> 
            <div style="flex-grow: 1; text-align: right;">
                <button class="spotify-button">
                    <i class="fab fa-spotify"></i>
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Login'; ?>
                </button>
                <?php if (isset($_SESSION['username'])): ?>
                    <form action="logout.php" method="POST" style="display:inline;">
                        <button type="submit" class="logout-btn" title="Logout">
                            <i class="fas fa-sign-out-alt"></i> 
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <h1>Welcome to Melody Hub!</h1>
        <p>Enjoy your music experience.</p>
        <?php
require("db.php");

$sql = "SELECT * FROM songs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $songsByCategory = [];
    $allSongs = []; 

    while ($row = $result->fetch_assoc()) {
        $allSongs[] = $row;
        $songsByCategory[$row['category']][] = $row; 
    }

    echo '<div class="song-container">';

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
    echo '</div>';

  
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
        echo '</div>'; 
    }

    echo '</div>'; 
} else {
    echo "<p>No songs available.</p>";
}

$conn->close();
?>

    </div>

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
    const userId = <?php echo isset($_SESSION['id']) ? json_encode($_SESSION['id']) : 'null'; ?>;

   
    console.log('User ID:', userId);


    if (userId === null) {
        alert('You need to be logged in to like a song.');
        return; 
    }

    $.ajax({
    type: 'POST',
    url: 'toggle_like.php',
    data: { song_id: songId, user_id: userId }, 
    success: function(response) {
        console.log('Raw response:', response); 
        const result = JSON.parse(response);
        alert(result.message); 
    },
    error: function() {
        alert('An error occurred while toggling like.');
    }
});
}

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
    
</script>

</body>
</html>