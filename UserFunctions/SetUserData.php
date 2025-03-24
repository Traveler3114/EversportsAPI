<?php
require_once 'db.php';
function SetUserData($id, $name, $surname, $email, $password) {
    $conn = openConnection();

    $checkPassword = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $checkPassword->bind_param("i", $id);
    $checkPassword->execute();
    $result = $checkPassword->get_result();
    $row = $result->fetch_assoc();
    $password_db = $row['password']; 

    if (!password_verify($password, $password_db)) {
        echo json_encode(["status" => "error", "message" => "Wrong password entered"]);
        return;
    }

    $stmt = $conn->prepare("UPDATE users SET name = ?, surname = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $surname, $email, $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User data updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update user data"]);
    }

    $checkPassword->close();
    $stmt->close();
    $conn->close();
}
?>
