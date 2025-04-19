<?php
require_once 'db.php';
require_once 'JWToken.php';
function DeleteUser($user_id, $jwt) {
    $decoded = VerifyToken($jwt);

    if ($decoded['status'] === 'error') {
        echo json_encode(["status" => "error", "message" => $decoded['message']]);
        return;
    }

    // Check if the user is authorized to delete this user
    if ($decoded['user_id'] !== $user_id) {
        echo json_encode(["status" => "error", "message" => "Unauthorized action"]);
        return;
    }

    $conn = openConnection();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete user or user not found"]);
    }

    $stmt->close();
}


?>