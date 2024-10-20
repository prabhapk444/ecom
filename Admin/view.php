<?php
session_start();
require("./db.php");

$sql = "SELECT * FROM songs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            width: 50%;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .card {
            background-color: white;
            width: 30%;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .card img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .song-details {
            display: flex;
            justify-content: space-between;
            width: 100%;
            text-align: left;
            margin-bottom: 10px;
        }

        .song-details h3 {
            margin: 0;
            font-size: 18px;
        }

        .song-details p {
            margin: 0;
            color: #555;
        }

        .controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }

        .controls button {
            background-color: #1DB954;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .controls button.stop {
            background-color: #d9534f;
        }

        .controls button:hover {
            opacity: 0.9;
        }

        audio {
            display: none;
        }

        @media (max-width: 992px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 90%;
            }

            .search-container input[type="text"] {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <div class="search-container">
        <input type="text" id="searchBar" onkeyup="searchSongs()" placeholder="Search for songs...">
    </div>

    <div class="container" id="songList">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="card">
                    <img src="' . $row['image_path'] . '" alt="Song Image">
                    <div class="song-details">
                        <h3>' . $row['title'] . '</h3>
                        <p>' . $row['author'] . '</p>
                    </div>
                    <audio id="audio-' . $row['id'] . '" src="' . $row['song_path'] . '"></audio>
                    <div class="controls">
                        <button class="play" onclick="playSong(' . $row['id'] . ')"><i class="fas fa-play"></i> Play</button>
                        <button class="stop" onclick="stopSong(' . $row['id'] . ')"><i class="fas fa-stop"></i> Stop</button>
                    </div>
                </div>';
            }
        } else {
            echo '<p>No songs available.</p>';
        }

        $conn->close();
        ?>
    </div>

    <script>
        let currentAudio = null;

        function playSong(id) {
            const audio = document.getElementById('audio-' + id);
            
            if (currentAudio && currentAudio.id !== 'audio-' + id) {
                currentAudio.pause();  
            }

            if (audio.paused) {
                audio.play();  
            }
            
            currentAudio = audio;  
        }

        function stopSong(id) {
            const audio = document.getElementById('audio-' + id);
            audio.pause();  
        }

        function searchSongs() {
            const input = document.getElementById('searchBar').value.toLowerCase();
            const songList = document.getElementById('songList');
            const cards = songList.getElementsByClassName('card');

            for (let i = 0; i < cards.length; i++) {
                const title = cards[i].getElementsByTagName('h3')[0].textContent.toLowerCase();
                const author = cards[i].getElementsByTagName('p')[0].textContent.toLowerCase();

                if (title.indexOf(input) > -1 || author.indexOf(input) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>
