<?php
require_once 'db.php';
function GetUserData($email) {
    $conn = openConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $user_data = $result->fetch_assoc();
    echo json_encode(["status" => "success", "user" => $user_data]);
}
?>
