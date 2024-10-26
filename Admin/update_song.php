<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}

require("./db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];

   
    $query = "SELECT song_path, image_path FROM songs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $song = $result->fetch_assoc();

    $songPath = $song['song_path']; 
    $imagePath = $song['image_path']; 

 
    if (!empty($_FILES['song']['name'])) {
        $newSongPath = 'songs/' . basename($_FILES['song']['name']);
        if (move_uploaded_file($_FILES['song']['tmp_name'], $newSongPath)) {
            $songPath = $newSongPath; 
        }
    }

    if (!empty($_FILES['image']['name'])) {
        $newImagePath = 'images/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)) {
            $imagePath = $newImagePath; 
        }
    }

   
    $sql = "UPDATE songs SET title = ?, author = ?, category = ?, song_path = ?, image_path = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $author, $category, $songPath, $imagePath, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo '<script>
            alert("Error: ' . $conn->error . '");
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
    <title>Update Song</title>
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
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
        input[type="text"], input[type="file"], input[type="number"] {
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
    <h2>Update Song</h2>
    <form id="updateForm" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="id">Song ID:</label>
            <input type="number" id="id" name="id" required>
        </div>
        <div class="form-group">
            <label for="title">Song Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="song">Mp3 File (Leave blank if not changing):</label>
            <input type="file" id="song" name="song" accept=".mp3">
        </div>
        <div class="form-group">
            <label for="image">Image File (Leave blank if not changing):</label>
            <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png">
        </div>
        <div class="form-group">
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
        </div>
        <button type="submit" class="btn btn-success">Update Song</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.getElementById('preloader').style.display = 'none';
            document.querySelector('.container').style.opacity = '1';
        }, 4000); 
    });

    document.getElementById('updateForm').addEventListener('submit', function(event) {
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
            if (!['image/jpeg', 'image/png'].includes(imageFile.type)) {
                alert('Please upload a valid image file (JPG, JPEG, or PNG).');
                event.preventDefault();
                return;
            }
        }
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
