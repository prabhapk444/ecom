<?php
require("./db.php");

$response = ['success' => false, 'message' => ''];

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
   
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response['success'] = true; 
    } else {
        $response['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

echo json_encode($response);
?>
