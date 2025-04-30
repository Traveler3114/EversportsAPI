<?php
require_once '../db.php';
require_once '../JWToken.php';


header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$user_id = $input['user_id'];
$jwt = $input['jwt'];

$decoded = VerifyToken($jwt);

if ($decoded['status'] === 'error') {
    echo json_encode(["status" => "error", "message" => $decoded['message']]);
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



?>