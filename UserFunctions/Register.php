<?php
require_once '../db.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$user = $input['user'];

// Extract values with validation
$name = $user['name'] ?? '';
$surname = $user['surname'] ?? '';
$email = $user['email'] ?? '';
$password = $user['password'] ?? '';


$conn = openConnection();



$checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$result = $checkEmail->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    return;
}

$password_hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $conn->prepare("INSERT INTO users (name, surname, email, password,role) VALUES (?, ?, ?, ?,?)");
$role="user";
$stmt->bind_param("sssss", $name, $surname, $email, $password_hash,$role);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registration successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Registration failed"]);
}
$checkEmail->close();
$stmt->close();
$conn->close();

?>
