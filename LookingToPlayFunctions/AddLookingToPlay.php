<?php
require_once 'db.php';

function AddLookingToPlay($availableDateTimes,$country, $city, $detailedLocation, $description, $user_id) {
    $conn = openConnection();
    
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
        $conn->close();

        echo json_encode(["status" => "success", "message" => "Added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Adding failed"]);
    }
}
?>