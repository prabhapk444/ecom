<?php
session_start(); 
if (!isset($_SESSION['email']) || !isset($_SESSION['loggedin']))  {
    header("Location: login.php");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./assets/css/preloader.css">
    <link rel="stylesheet" href="./assets/css/search.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Search Songs</title>
</head>
<body>

    <div id="preloader" class="preloader">
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
    </div>

<div class="content">
    <div class="search-container">
        <input type="text" id="searchBar" onkeyup="searchSongs()" placeholder="Search for songs...">
    </div>

    <div class="sort-container">
        <button class="sort-btn" onclick="sortSongs('author', 'asc')">Sort by Author (A-Z)</button>
        <button class="sort-btn" onclick="sortSongs('author', 'desc')">Sort by Author (Z-A)</button>
        <button class="sort-btn" onclick="sortSongs('title', 'asc')">Sort by Song Name (A-Z)</button>
        <button class="sort-btn" onclick="sortSongs('title', 'desc')">Sort by Song Name (Z-A)</button>
        <button class="sort-btn" onclick="sortSongs('created_at', 'asc')">Sort by Date (Oldest First)</button>
        <button class="sort-btn" onclick="sortSongs('created_at', 'desc')">Sort by Date (Newest First)</button>
    </div>

    <?php
    require("db.php");

    $sql = "SELECT * FROM songs";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $songs = [];
        while ($row = $result->fetch_assoc()) {
            $songs[] = $row;  
        }

        echo '<ul class="song-list" id="songList">';
        $serialNumber = 1;  
        foreach ($songs as $row) {
            $songTitle = $row['title'];
            $songAuthor = $row['author'];
            $songPath = 'Admin/' . $row['song_path'];
            $songImagePath = 'Admin/' . $row['image_path'];
            $createdAt = $row['created_at'];  

            echo '
            <li class="song-item" data-title="' . $songTitle . '" data-author="' . $songAuthor . '" data-created_at="' . $createdAt . '" data-id="' . $row['id'] . '">
                <div class="song-img">
                    <img src="' . $songImagePath . '" alt="Song Image" loading="lazy">
                </div>
                <div class="song-info">
                    <h2>' . $serialNumber . '. ' . $songTitle . '</h2>
                    <p>' . $songAuthor . '</p>
                </div>
                <audio id="audio-' . $row['id'] . '" src="' . $songPath . '" onended="playNextSong(' . $row['id'] . ')"></audio>
                <div class="song-controls">
                    <button class="play-btn" onclick="playSong(' . $row['id'] . ')"><i class="fas fa-play"></i></button>
                    <button class="stop-btn" onclick="stopSong(' . $row['id'] . ')"><i class="fas fa-stop"></i></button>
                    <a href="' . $songPath . '" download class="download-btn"><i class="fas fa-download"></i></a>
                    <button class="share-btn" onclick="shareSong(\'' . $songTitle . '\', \'' . $songAuthor . '\', \'' . $songPath . '\')"><i class="fas fa-share"></i></button>
                </div>
            </li>';
            
            $serialNumber++;  
        }
        echo '</ul>';
    } else {
        echo "<p>No songs available.</p>";
    }

    $conn->close();
    ?>

</div>
    
    <script>

        
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.getElementById('preloader').style.display = 'none';
        document.querySelector('.content').style.opacity = '1';
    }, 4000); 
});

document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll("img.lazy-load");
    images.forEach(img => {
        img.src = img.dataset.src;
    });
});



        let songsArray = [];

                document.querySelectorAll('.song-item').forEach(item => {
            songsArray.push(item);
        });

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
                alert('Sharing not supported on this device.');
            }
        }

        function searchSongs() {
            let input = document.getElementById('searchBar').value.toLowerCase();
            let songItems = document.querySelectorAll('.song-item');

            songItems.forEach(item => {
                let title = item.querySelector('.song-info h2').innerText.toLowerCase();
                let author = item.querySelector('.song-info p').innerText.toLowerCase();
                if (title.includes(input) || author.includes(input)) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        }

        function playSong(id) {
            let audioElement = document.getElementById('audio-' + id);
            let allAudios = document.querySelectorAll('audio');
            allAudios.forEach(audio => {
                if (audio !== audioElement) {
                    audio.pause();
                }
            });
            audioElement.play();
        }

        function stopSong(id) {
            let audioElement = document.getElementById('audio-' + id);
            audioElement.pause();
            audioElement.currentTime = 0;
        }

        function sortSongs(attribute, direction) {
            let songsList = document.querySelector('.song-list');
            let songs = Array.from(songsList.children);

            songs.sort((a, b) => {
                let aValue = a.getAttribute('data-' + attribute).toLowerCase();
                let bValue = b.getAttribute('data-' + attribute).toLowerCase();

                if (direction === 'asc') {
                    return aValue.localeCompare(bValue);
                } else {
                    return bValue.localeCompare(aValue);
                }
            });

            songsList.innerHTML = "";
            songs.forEach((song, index) => {
                song.querySelector('.song-info h2').innerText = (index + 1) + '. ' + song.querySelector('.song-info h2').innerText.split('. ')[1]; // Update serial number
                songsList.appendChild(song);
            });
        }
    </script>

</body>
</html>
