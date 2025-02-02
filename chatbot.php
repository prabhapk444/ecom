<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "music");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "user";
}

$username = $_SESSION['username'];

$data = json_decode(file_get_contents('php://input'), true);
$user_message = $data['message'];

$response = "";

if (stripos($user_message, 'hi') !== false) {
    $response = "Hi, $username! 🎶 I'm your music assistant. How can I help you today?\nI can assist you with:\n- songs\n-categories\n- And more!";
} elseif (stripos($user_message, 'how are you') !== false) {
    $response = "I'm doing great, thanks for asking, $username! 😊 How can I assist you with your music today?";
} elseif (stripos($user_message, 'about') !== false) {
    $response = "I am your friendly music bot, $username, here to help you discover new songs, and keep track of your music preferences. 🎵";
} elseif (stripos($user_message, 'thank you') !== false) {
    $response = "You're welcome, $username! 😊 I'm glad I could help you. Let me know if you need anything else!";
} elseif (stripos($user_message, 'bye') !== false) {
    $response = "Goodbye, $username! 👋";
} elseif (stripos($user_message, 'songs') !== false) {
  
    $query = "SELECT * FROM songs";
    $result = $mysqli->query($query);
    $songs = [];

    while ($row = $result->fetch_assoc()) {
        $songs[] = $row['title'];
    }


    if (count($songs) > 0) {
        $response .= "🎵 *Here are the available songs:*\n";
        foreach ($songs as $index => $song) {
            $response .= "*" . $song . "*\n"; 
        }
    } else {
        $response .= "❌ No songs available at the moment.";
    }
} elseif (stripos($user_message, 'categories') !== false) {
   
    $response .= "\n\n📌 *Available Categories:*\n";
    $query = "SELECT DISTINCT category FROM songs";
    $result = $mysqli->query($query);
    $categories = [];

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }

    if (count($categories) > 0) {
        $response .= implode("\n", $categories);
    } else {
        $response .= "❌ No categories available at the moment.";
    }
} else {
    $response = "🤔 I'm sorry, I didn't understand that. You can ask me about available songs or categories.";
}

echo json_encode(['response' => $response]);

$mysqli->close();
?>
