<?php
    session_start();
    
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Access Denied',
                text: 'You must be logged in to access the dashboard.',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location.href = 'adminlogin.php';
            });
        </script>";
        exit;
    }
    $adminUsername = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    display: flex;
    height: 100vh;
    background-color: #1e1e1e;
    color: #fff;
}

.sidebar {
    width: 250px;
    background-color: #000;
    color: #b3b3b3;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: fixed;
    height: 100%;
    transition: width 0.3s;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.sidebar.collapsed {
    width: 80px;
}

.logo {
    text-align: center;
    padding: 20px 0;
    font-size: 1.5rem;
    font-weight: bold;
    background-color: #121212;
    border-bottom: 1px solid #282828;
}

.logo i {
    color: #1db954;
    margin-right: 10px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    padding: 20px;
    cursor: pointer;
    transition: background 0.3s, color 0.3s;
    display: flex;
    align-items: center;
    position: relative;
}

.sidebar ul li:hover {
    background-color: #282828;
    color: #fff;
}

.sidebar ul li i {
    margin-right: 15px;
    font-size: 1.2rem;
    min-width: 20px;
    text-align: center;
}

.sidebar.collapsed ul li span {
    display: none;
}

.sidebar.collapsed ul li:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 100%;
    margin-left: 10px;
    background-color: #333;
    padding: 5px 10px;
    border-radius: 4px;
    white-space: nowrap;
    color: #fff;
    opacity: 0;
    animation: fadeIn 0.3s forwards;
    pointer-events: none;
}

@keyframes fadeIn {
    to {
        opacity: 1;
    }
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
    transition: margin-left 0.3s, width 0.3s;
}

.sidebar.collapsed + .main-content {
    margin-left: 80px;
    width: calc(100% - 80px);
}


.username-marquee {
    font-weight: bold;
    font-size: 1.5rem;
    margin-bottom: 20px;
    overflow: hidden;
    white-space: nowrap;
    box-sizing: border-box;
    animation: marquee 10s linear infinite, changeColor 2s infinite;
}

@keyframes marquee {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(-100%);
    }
}

@keyframes changeColor {
    0% {
        color: #1db954;
    }
    25% {
        color: #ff5733;
    }
    50% {
        color: #33c5ff;
    }
    75% {
        color: #ffc300;
    }
    100% {
        color: #1db954;
    }
}


.toggle-btn {
    background: none;
    border: none;
    color: #b3b3b3;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 10px;
    width: 100%;
    text-align: right;
    transition: color 0.3s;
}

.toggle-btn:hover {
    color: #fff;
}

.toggle-btn:focus {
    outline: none;
}


.counter {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
    background-color: #121212;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    justify-content: center; 
}

.counter .col {
    flex: 1 1 calc(25% - 20px); 
    min-width: 180px;
    margin-bottom: 20px;
    padding: 20px;
    background-color: #1e1e1e;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-align: center;
    color: white;
}

.counter .col:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.counter .col i {
    font-size: 25px;
    color: #1db954;
    margin-bottom: 10px;
}

.counter .col p {
    font-size: 10px;
    font-weight: bold;
    margin: 10px 0;
    color: #b3b3b3;
}

.counter .col .counter-value {
    font-size: 36px;
    font-weight: bold;
    color: white;
    transition: color 0.3s ease;
}

.counter .col:hover .counter-value {
    color: #ff6347;
}

@media (max-width: 992px) {
    .sidebar {
        width: 80px;
    }

    .main-content {
        margin-left: 80px;
        width: calc(100% - 80px);
    }

    .counter .col {
        flex: 1 1 calc(50% - 20px); 
    }

    .toggle-btn {
        position: fixed;
        bottom: 10px;
        right: 10px;
        z-index: 1001;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 80px;
    }

    .main-content {
        margin-left: 80px;
        width: calc(100% - 80px);
    }

    .counter .col {
        flex: 1 1 calc(100% - 20px); 
    }
}

    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div>
            <div class="logo">
                <i class="fas fa-music"></i>
                <span>Admin</span>
            </div>
            <ul>
                <li onclick="navigateTo('songs.php')" data-tooltip="Songs">
                     <i class="fas fa-music"></i>
                    <span>Songs</span>
                </li>
                <li onclick="navigateTo('users.php')" data-tooltip="Users">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </li>
                <li onclick="navigateTo('settings.php')" data-tooltip="Settings">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </li>
                <li onclick="navigateTo('logout.php')" data-tooltip="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </li>
            </ul>
        </div>
        <button class="toggle-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    <div class="main-content">
        <div class="container">
            <div class="username-marquee">
                Welcome <?php echo $adminUsername; ?>!
            </div>
            <h1>Dashboard Overview</h1>
        </div>

    <div class="counter"><div class="counter">
  <?php
    include("./db.php");

    $SongsCountQuery = "SELECT COUNT(*) AS total_songs FROM songs";
    $SongResult = $conn->query($SongsCountQuery);

    $UsersCountQuery = "SELECT COUNT(*) AS total_users FROM users";
    $UsersResult = $conn->query($UsersCountQuery);

    $LikesCountQuery = "SELECT COUNT(*) AS total_likedsongs FROM liked_songs";
    $LikesResult = $conn->query($LikesCountQuery);

    if ($SongResult === false || $UsersResult === false || $LikesResult === false) {
        echo "<div class='col'>Error retrieving data: " . $conn->error . "</div>";
    } else {
        $songRow = $SongResult->fetch_assoc();
        $totalSongs = $songRow['total_songs'];

        $userRow = $UsersResult->fetch_assoc();
        $totalusers = $userRow['total_users'];

        $likesRow = $LikesResult->fetch_assoc();
        $totallikes = $likesRow['total_likedsongs'];

        echo "<div class='col'>";
        echo "<i class='fas fa-box'></i>";
        echo "<p class='counter-value' style='font-size: 18px;'>$totalSongs Songs</p>";  
        echo "</div>";
        
        echo "<div class='col'>";
        echo "<i class='fas fa-users'></i>";
        echo "<p class='counter-value' style='font-size: 18px;'>$totalusers Users</p>";  
        echo "</div>";

        echo "<div class='col'>";
        echo "<i class='fas fa-heart'></i>";
        echo "<p class='counter-value' style='font-size: 18px;'>$totallikes Liked Songs</p>";  
        echo "</div>";
    }
  ?>
</div>


    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.toggle-btn i');

        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                toggleBtn.classList.remove('fa-chevron-left');
                toggleBtn.classList.add('fa-chevron-right');
            } else {
                toggleBtn.classList.remove('fa-chevron-right');
                toggleBtn.classList.add('fa-chevron-left');
            }
        }

        function navigateTo(page) {
            if(page === 'logout.php') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = page;
                    }
                });
            } else {
                window.location.href = page;
            }
        }
    </script>
</body>
</html>
