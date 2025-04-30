<?php
require_once 'db.php';
require_once 'JWToken.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$lookingToPlay=$input['lookingToPlay'] ?? null;

$availableDateTimes = $lookingToPlay['availableDateTimes'] ?? null;
$country = $lookingToPlay['country'] ?? null;
$city = $lookingToPlay['city'] ?? null;
$detailedLocation = $lookingToPlay['detailedLocation'] ?? null;
$choosenSports = $lookingToPlay['choosenSports'] ?? null;
$description = $lookingToPlay['description'] ?? null;
$jwt = $lookingToPlay['jwt'] ?? null;



$conn = openConnection();
$decoded=VerifyToken($jwt);
$user_id = $decoded['user_id'];

// Insert into lookingtoplay
$stmt = $conn->prepare("INSERT INTO lookingtoplay (country, city, detailedLocation, description, user_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $country, $city, $detailedLocation, $description, $user_id);

if ($stmt->execute()) {
    $lookingToPlayId = $stmt->insert_id; // Get the last inserted ID
    $stmt->close();

    // Insert available date times
    $stmt = $conn->prepare("INSERT INTO availabledatetime (Date, FromTime, ToTime, lookingtoplay_id) VALUES (?, ?, ?, ?)");
    
    foreach ($availableDateTimes as $availableDateTime) {
        $stmt->bind_param("sssi", $availableDateTime['Date'], $availableDateTime['FromTime'], $availableDateTime['ToTime'], $lookingToPlayId);
        $stmt->execute();
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO choosensport (name, lookingtoplay_id) VALUES (?, ?)");
    
    foreach ($choosenSports as $sport) {
        $stmt->bind_param("si", $sport, $lookingToPlayId);
        $stmt->execute();
    }
    $stmt->close();

    $conn->close();

    echo json_encode(["status" => "success", "message" => "Added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Adding failed"]);
}

?>