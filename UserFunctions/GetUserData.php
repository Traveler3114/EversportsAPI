<?php
require_once 'db.php';
require_once 'JWToken.php';

function GetUserData($jwt, $requested_user_id = null) {
    $decoded = VerifyToken($jwt);

    if ($decoded['status'] === 'error') {
        echo json_encode(["status" => "error", "message" => $decoded['message']]);
        return;
    }

    // If no user_id provided, use JWT user
    $user_id = $requested_user_id ?? $decoded['user_id'];

    $conn = openConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $user_data = $result->fetch_assoc();
    echo json_encode(["status" => "success", "obj" => $user_data]);
}

?>
