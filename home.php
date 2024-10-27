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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melody Hub</title>
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>


<button class="toggle-sidebar" onclick="toggleSidebar()">☰</button>
<div class="sidebar" id="sidebar">
<a href="search.php"><i class="fas fa-search"></i> Search</a>
    <a href="likedsong.php"><i class="fas fa-heart"></i> Liked Songs</a>
    <a href="account.php"><i class="fas fa-user"></i> Profile</a>
    <?php
    require("db.php");
    $categoryQuery = "SELECT DISTINCT category FROM songs";
    $categoryResult = $conn->query($categoryQuery);

    if ($categoryResult->num_rows > 0) {
        echo '<div class="category-links">
        <h4>Songs Categories</h4>';
        while ($categoryRow = $categoryResult->fetch_assoc()) {
            $category = htmlspecialchars($categoryRow['category']);
            echo '<a href="#' . $category . '">' . $category . '</a>';
        }
        echo '</div>';
    }
    ?>
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
            echo '<h3 id="' . htmlspecialchars($category) . '" class="category-title">' . htmlspecialchars($category) . '</h3>';
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

    <script src="./assets/js/player.js"></script>

    <script>
        
    function toggleLike(songId) {
    const userId = <?php echo isset($_SESSION['id']) ? json_encode($_SESSION['id']) : 'null'; ?>;

    if (userId === null) {
        alert('You need to be logged in to like a song.');
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'toggle_like.php',
        data: { song_id: songId, user_id: userId },
        dataType: 'json', 
        success: function(response) {
            console.log('Raw response:', response);
            if (typeof response === 'object') {
                handleResponse(response, songId);
            } else {
                try {
                    const result = JSON.parse(response);
                    handleResponse(result, songId);
                } catch (error) {
                    console.error('Error parsing response:', error, 'Response received:', response);
                    Swal.fire({
                        title: 'Error',
                        text: 'An unexpected error occurred while processing the response.',
                        icon: 'error'
                    });
                }
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while toggling like.',
                icon: 'error'
            });
        }
    });
}

function handleResponse(result, songId) {
    if (result.success) {
        const likeButton = document.getElementById('like-btn-' + songId);
        if (result.liked) {
            likeButton.classList.add('liked');
        } else {
            likeButton.classList.remove('liked');
        }

        Swal.fire({
            title: 'Success',
            text: result.message,
            icon: 'success'
        });
    } else {
        Swal.fire({
            title: 'Error',
            text: result.message,
            icon: 'error'
        });
    }
}

  
    
    
</script>


</body>
</html>