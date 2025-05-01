<?php
require_once '../db.php';
require_once '../JWToken.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$user = $input['user'] ?? null;

$id = $user['id'] ?? null;
$name = $user['name'] ?? '';
$surname = $user['surname'] ?? '';
$email = $user['email'] ?? '';
$password = $user['password'] ?? '';
$jwt = $input['jwt'];

$decoded=VerifyToken($jwt);
if ($decoded['status'] === 'error') {
    echo json_encode(["status" => "error", "message" => $decoded['message']]);
    return;
}

$user_id = $decoded['user_id'] ?? null;

$conn = openConnection();

$checkPassword = $conn->prepare("SELECT * FROM users WHERE id = ?");
$checkPassword->bind_param("i", $user_id);
$checkPassword->execute();
$result = $checkPassword->get_result();
$row = $result->fetch_assoc();

if (!$row || !password_verify($password, $row['password'])) {
    echo json_encode(["status" => "error", "message" => "Wrong password entered"]);
    return;
}

$stmt = $conn->prepare("UPDATE users SET name = ?, surname = ?, email = ? WHERE id = ?");
$stmt->bind_param("sssi", $name, $surname, $email, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "User data updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update user data"]);
}

$checkPassword->close();
$stmt->close();
$conn->close();

?>
