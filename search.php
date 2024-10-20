<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Search Songs</title>
    <style>
        * {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #121212;
            color: #fff;
            padding: 20px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            width: 50%;
            border-radius: 50px;
            border: none;
            font-size: 16px;
            outline: none;
            background-color: #282828;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .search-container input[type="text"]::placeholder {
            color: #b3b3b3;
        }

        .like-btn {
            background-color: #ff4081;
            border: none;
            color: white;
            border-radius: 50%;
            padding: 8px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .like-btn:hover {
            background-color: #ff80ab;
        }

        .sort-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .sort-btn {
            padding: 10px;
            background-color: #1db954;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
            font-size: 14px;
        }

        .sort-btn:hover {
            background-color: #1ed760;
        }

        .song-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .song-item {
            display: flex;
            align-items: center;
            background-color: #181818;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .song-img img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
        }

        .song-info {
            flex: 1;
        }

        .song-info h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .song-info p {
            font-size: 14px;
            color: #b3b3b3;
        }

        .song-controls {
            display: flex;
            align-items: center;
        }

        .song-controls button, .download-btn, .share-btn {
            background-color: #1db954;
            border: none;
            color: white;
            border-radius: 50%;
            padding: 8px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
            margin-left: 10px;
        }

        .song-controls button i, .download-btn i, .share-btn i {
            font-size: 16px;
        }

        .song-controls button:hover, .download-btn:hover, .share-btn:hover {
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
            .search-container input[type="text"] {
                width: 80%;
            }

            .sort-btn {
                font-size: 12px;
                padding: 8px;
            }

            .song-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
            }

            .song-img img {
                width: 50px;
                height: 50px;
            }

            .song-info h2 {
                font-size: 14px;
            }

            .song-info p {
                font-size: 18px;
            }

            .song-controls {
                width: 100%;
                justify-content: space-between;
            }

            .song-controls button, .download-btn, .share-btn {
                padding: 6px;
                font-size: 12px;
            }
        }


        @media screen and (max-width: 768px) {
            .song-info h2 {
                font-size: 14px;
            }

            .song-info p {
                font-size: 10px;
            }

            .song-img img {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>

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
            $songs[] = $row;  // Store all songs in an array
        }

        echo '<ul class="song-list" id="songList">';
        $serialNumber = 1;  // Initialize the serial number
        foreach ($songs as $row) {
            $songTitle = $row['title'];
            $songAuthor = $row['author'];
            $songPath = 'Admin/' . $row['song_path'];
            $songImagePath = 'Admin/' . $row['image_path'];
            $createdAt = $row['created_at'];  // Assuming you have this field in your database

            echo '
            <li class="song-item" data-title="' . $songTitle . '" data-author="' . $songAuthor . '" data-created_at="' . $createdAt . '" data-id="' . $row['id'] . '">
                <div class="song-img">
                    <img src="' . $songImagePath . '" alt="Song Image">
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
            
            $serialNumber++;  // Increment the serial number
        }
        echo '</ul>';
    } else {
        echo "<p>No songs available.</p>";
    }

    $conn->close();
    ?>

    <script>
        let songsArray = [];

        // Store the songs as an array for sorting
        document.querySelectorAll('.song-item').forEach(item => {
            songsArray.push(item);
        });

        // Function to share the song
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
