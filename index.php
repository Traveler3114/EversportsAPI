<?php
require_once 'UserFunctions/Login.php';
require_once 'UserFunctions/Register.php';
require_once 'UserFunctions/SetUserData.php';
require_once 'UserFunctions/GetUserData.php';
require_once 'LookingToPlayFunctions/AddLookingToPlay.php';
require_once 'LookingToPlayFunctions/GetLookingToPlay.php';
require_once 'JWToken.php';
require_once 'Messaging/SendMessage.php';
require_once 'Messaging/GetAllMessages.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    //Registration
    if ($input['action'] === 'register') {
        register($input['user']['name'], $input['user']['surname'], $input['user']['email'], $input['user']['password']);
    }
    //Login
    elseif ($input['action'] === 'login') {
        login($input['user']['email'], $input['user']['password'],$input['remember_me']);
    } 
    //getUserData
    elseif ($input['action'] === 'getUserData') {
        $requested_id = $input['user_id'] ?? null;
        GetUserData($input['jwt'], $requested_id);
    }
    //setUserData
    elseif ($input['action'] === 'setUserData') {
        SetUserData($input['user']['id'], $input['user']['name'], $input['user']['surname'], $input['user']['email'], $input['user']['password'],$input['jwt']);
    }
    //AddLookingToPlay
    elseif($input['action'] === 'AddLookingToPlay'){
        AddLookingToPlay(  $input['lookingToPlay']['availableDateTimes'],$input['lookingToPlay']['country'], $input['lookingToPlay']['city'], $input['lookingToPlay']['detailedLocation'],$input['lookingToPlay']['choosenSports'], $input['lookingToPlay']['description'],$input['lookingToPlay']['user_id']);
    }
    //GetLookingToPlay
    elseif($input['action'] === 'GetLookingToPlay'){
        GetLookingToPlay($input['country'],$input['city'],$input['Dates'],$input['FromTimes'],$input['ToTimes'],$input['choosenSports']);
    }
    //verifyToken
    elseif($input['action'] === 'verifyToken'){
        $response = VerifyToken($input['jwt']);
        echo json_encode($response);  // Return the result as JSON
    }
    //SendMessage
    elseif($input['action'] === 'sendMessage'){
        SendMessage($input['jwt'],$input['lookingtoplay_id'], $input['message']);
    }
    //GetAllMessages
    elseif($input['action'] === 'getAllMessages'){
        GetAllMessages($input['lookingtoplay_id']);
    }

    else {
        echo json_encode(["status" => "error", "message" => "Invalid input or action"]);
    }
} 

else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
