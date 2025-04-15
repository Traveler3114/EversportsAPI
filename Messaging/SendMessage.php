<?php
require_once 'JWToken.php';
require_once 'db.php';

function SendMessage($jwt,$lookingtoplay_id, $message) {
    $conn = openConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $decoded = VerifyToken($jwt);
    if ($decoded['status'] === 'error') {
        echo json_encode(["status" => "error", "message" => $decoded['message']]);
        return;
    }

    $sender_id = $decoded['user_id'];


    $stmt = $conn->prepare("INSERT INTO lookingtoplaychat (sender_id, lookingtoplay_id,encrypted_message) VALUES (?,?,?)");
    $stmt->bind_param("iis", $sender_id,$lookingtoplay_id, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Message sent successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send message"]);
    }
    $stmt->close();
    $conn->close();
}
?>