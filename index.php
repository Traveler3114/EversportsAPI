<?php
require_once 'UserFunctions/Login.php';
require_once 'UserFunctions/Register.php';
require_once 'UserFunctions/SetUserData.php';
require_once 'UserFunctions/GetUserData.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input['action'] === 'register') {
        register($input['user']['name'], $input['user']['surname'], $input['user']['email'], $input['user']['password']);
    } 
    elseif ($input['action'] === 'login') {
        login($input['user']['email'], $input['user']['password']);
    } 
    elseif ($input['action'] === 'getData') {
        GetUserData($input['user']['email']);
    }
    elseif ($input['action'] === 'setData') {
        SetUserData($input['user']['id'], $input['user']['name'], $input['user']['surname'], $input['user']['email'], $input['user']['password']);
    }
    else {
        echo json_encode(["status" => "error", "message" => "Invalid input or action"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
