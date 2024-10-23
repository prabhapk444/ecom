<?php
require("db.php");

$data = json_decode(file_get_contents("php://input"));
$songId = $data->id;

// Here you would check if the song is already liked or not, 
// and then toggle the like status in the database accordingly.
// Assuming you have a `liked_songs` table with `user_id` and `song_id` columns.

session_start();
$userId = $_SESSION['user_id']; // Assuming you have user ID in session

$checkLike = $conn->prepare("SELECT * FROM liked_songs WHERE user_id = ? AND song_id = ?");
$checkLike->bind_param("ii", $userId, $songId);
$checkLike->execute();
$result = $checkLike->get_result();

if ($result->num_rows > 0) {
    // Already liked, so remove like
    $deleteLike = $conn->prepare("DELETE FROM liked_songs WHERE user_id = ? AND song_id = ?");
    $deleteLike->bind_param("ii", $userId, $songId);
    $success = $deleteLike->execute();
} else {
    // Not liked, so add like
    $addLike = $conn->prepare("INSERT INTO liked_songs (user_id, song_id) VALUES (?, ?)");
    $addLike->bind_param("ii", $userId, $songId);
    $success = $addLike->execute();
}

echo json_encode(['success' => $success]);
$conn->close();
?>
