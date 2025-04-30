<?php
require_once '../db.php';
require_once '../JWToken.php';

header('Content-Type: application/json');

// Get the query parameters
$jwt = $_GET['jwt'] ?? null;
$howmany = $_GET['howmany'] ?? null;

if (!$jwt) {
    echo json_encode(["status" => "error", "message" => "JWT parameter is missing"]);
    exit;
}

if ($howmany==1 ) {
    GetUserData($jwt);  // If user_id is provided, fetch specific user data
} else {
    GetAllUsers($jwt);  // If no user_id is provided, fetch all users
}

function GetUserData($jwt) {
    $decoded = VerifyToken($jwt);

    if ($decoded['status'] === 'error') {
        echo json_encode(["status" => "error", "message" => $decoded['message']]);
        return;
    }

    $user_id = $decoded['user_id'];

    $conn = openConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $user_data = $result->fetch_assoc();
    echo json_encode(["status" => "success", "obj" => $user_data]);

    $stmt->close();
    $conn->close();
}

function GetAllUsers($jwt) {
    $decoded = VerifyToken($jwt);

    if ($decoded['status'] === 'error') {
        echo json_encode(["status" => "error", "message" => $decoded['message']]);
        return;
    }

    $conn = openConnection();
    $stmt = $conn->prepare("SELECT id, name, surname, email, role FROM users");
    $stmt->execute();
    $result = $stmt->get_result();

    $users_data = array();
    while ($row = $result->fetch_assoc()) {
        $users_data[] = $row;
    }

    echo json_encode(["status" => "success", "obj" => $users_data]);
    $stmt->close();
    $conn->close();
}
?>
