<?php
require("./db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Prepare the SQL DELETE statement
    $sql = "DELETE FROM songs WHERE id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute the statement
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
    <title>Delete Song</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
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
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Song</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="id">Song ID:</label>
                <input type="number" id="id" name="id" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete Song</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
