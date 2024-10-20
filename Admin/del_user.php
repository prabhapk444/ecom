<?php
require("./db.php");

$response = ['success' => false, 'message' => ''];

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Ensure ID is an integer to prevent SQL injection

    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response['success'] = true; // Deletion successful
    } else {
        $response['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

// Return JSON response
echo json_encode($response);
?>
