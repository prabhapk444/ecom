<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
$song_id = isset($_POST['song_id']) ? $_POST['song_id'] : null;

$response = [];

if ($user_id && $song_id) {

    $query = "SELECT * FROM liked_songs WHERE user_id = ? AND song_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $user_id, $song_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $query = "DELETE FROM liked_songs WHERE user_id = ? AND song_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $user_id, $song_id);
        if ($stmt->execute()) {
            $response['message'] = 'Song unliked successfully!';
        } else {
            $response['message'] = 'Error unliking the song.';
        }
    } else {
        
        $query = "INSERT INTO liked_songs (user_id, song_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $user_id, $song_id);
        if ($stmt->execute()) {
            $response['message'] = 'Song liked successfully!';
        } else {
            $response['message'] = 'Error liking the song.';
        }
    }
} else {
    $response['message'] = 'Invalid request.';
}


header('Content-Type: application/json'); 
echo json_encode($response); 
exit; 
?>
