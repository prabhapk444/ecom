<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}


require("./db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];

    $songPath = 'songs/' . basename($_FILES['song']['name']);
    $imagePath = 'images/' . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['song']['tmp_name'], $songPath) && move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
       
        
        $sql = "INSERT INTO songs (title, song_path, image_path, author, category) VALUES ('$title', '$songPath', '$imagePath', '$author', '$category')";
        
        if ($conn->query($sql) === TRUE) {
          
            header("Location: dashboard.php");
            exit();
        } else {
           
            echo '<script>
                alert("Error: ' . $conn->error . '");
            </script>';
        }
    } else {
       
        echo '<script>
            alert("Failed to upload files.");
        </script>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Songs</title>
    <link rel="stylesheet" href="./../assets/css/preloader.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            opacity: 0;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .song-actions {
            margin-top: 20px;
            text-align: center;
        }

        .song-actions a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 15px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            background-color: #007bff;
        }

        .song-actions a:hover {
            background-color: #0056b3;
        }

        .song-actions a i {
            margin-right: 5px;
        }

        .song-actions a.delete {
            background-color: #d9534f;
        }

        .song-actions a.delete:hover {
            background-color: #c9302c;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            .song-actions a {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

    <div id="preloader" class="preloader">
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
    </div>

    <div class="container">
    <h2>Upload Song</h2>
    <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Song Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="song">Mp3 File:</label>
            <input type="file" id="song" name="song" accept=".mp3" required>
        </div>
        <div class="form-group">
            <label for="image">Image File:</label>
            <input type="file" id="image" name="image" accept=".jpg, .jpeg" required>
        </div>
        <div class="form-group">
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
        </div>
        <button type="submit" class="btn btn-success">Upload Song</button>
    </form>

    <div class="song-actions">
        <a href="update_song.php" class="update"><i class="fas fa-edit"></i> Update Song</a>
        <a href="delete_song.php" class="delete"><i class="fas fa-trash-alt"></i> Delete Song</a>
        <a href="view.php"><i class="fas fa-eye"></i> View Songs</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.getElementById('preloader').style.display = 'none';
            document.querySelector('.container').style.opacity = '1';
        }, 4000); 
    });

    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        const songInput = document.getElementById('song');
        const imageInput = document.getElementById('image');

        if (songInput.files.length > 0) {
            const songFile = songInput.files[0];
            if (songFile.type !== 'audio/mpeg') {
                alert("Please upload a valid MP3 file.");
                event.preventDefault();
                return;
            }
        }

        if (imageInput.files.length > 0) {
            const imageFile = imageInput.files[0];
            if (!['image/jpeg', 'image/jpg'].includes(imageFile.type)) {
                alert('Please upload a valid image file (JPG, JPEG).');
                event.preventDefault();
                return;
            }
        }
    });
</script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
