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
        <div class="song-container">
        <?php include './actions/Allsong.php';?>
        </div>
    </div>

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
    
</script>

</body>
</html>