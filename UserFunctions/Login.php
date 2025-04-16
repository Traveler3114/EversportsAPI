<?php
require_once 'db.php';
require_once 'JWToken.php';

function login($email, $password) {
    $conn = openConnection();
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows <= 0) {
        echo json_encode(["status" => "error", "message" => "Account does not exist"]);
        return;
    }

    $row = $result->fetch_assoc();
    $password_db = $row['password']; 
    if (password_verify($password, $password_db)) {

        $jwt= CreateToken($row['id'], $row['email'],$row['role']); // Create JWT token using the user's ID and email

        //echo json_encode(["status" => "success","message" => "Login successful"]);
        echo json_encode(["status" => "success","message" => "Login successful","token" => $jwt]);
    } else {
        echo json_encode(["status" => "error", "message" => "You entered the wrong password"]);
    }
    $checkEmail->close();
    $conn->close();
}
?>
