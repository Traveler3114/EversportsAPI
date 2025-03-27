<?php
require_once 'UserFunctions/Login.php';
require_once 'UserFunctions/Register.php';
require_once 'UserFunctions/SetUserData.php';
require_once 'UserFunctions/GetUserData.php';
require_once 'LookingToPlayFunctions/AddLookingToPlay.php';
require_once 'LookingToPlayFunctions/GetAllLookingToPlay.php';

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
    elseif($input['action'] === 'AddLookingToPlay'){
        AddLookingToPlay(  $input['lookingToPlay']['availableDateTimes'],$input['lookingToPlay']['country'], $input['lookingToPlay']['city'], $input['lookingToPlay']['detailedLocation'],$input['lookingToPlay']['choosenSports'], $input['lookingToPlay']['description'],$input['lookingToPlay']['user_id']);
    }
    else {
        echo json_encode(["status" => "error", "message" => "Invalid input or action"]);
    }
} 
else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    GetAllLookingToPay();
}
else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
