<?php
require_once '../db.php';
require_once '../JWToken.php';

header('Content-Type: application/json');



$jwt = $_GET['jwt']??null;
$fetchall=$_GET['fetchall']??null;
$user_id = $_GET['userid'] ?? null;


$decoded = VerifyToken($jwt);
if ($decoded['status'] !== 'success') {
    echo json_encode($decoded);
    exit;
}


$userid = $_GET['userid'] ?? null;


if ($userid !== null) {
    GetUserData($userid);
} elseif (filter_var($fetchall, FILTER_VALIDATE_BOOLEAN)) {
    GetAllUsers();
} else {
    GetUserData(intval($decoded['user_id']));
}

function GetUserData($userid) {

    $user_id = $userid;

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

function GetAllUsers() {

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
