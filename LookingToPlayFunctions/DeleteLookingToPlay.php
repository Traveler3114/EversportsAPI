<?php
require_once 'db.php';
function DeleteLookingToPlay($lookingtoplay_id) {
    $conn = openConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement to delete from availabledatetime and choosensport tables
    $stmt1 = $conn->prepare("DELETE FROM availabledatetime WHERE lookingtoplay_id = ?");
    $stmt1->bind_param("i", $lookingtoplay_id);
    $stmt1->execute();

    $stmt2 = $conn->prepare("DELETE FROM choosensport WHERE lookingtoplay_id = ?");
    $stmt2->bind_param("i", $lookingtoplay_id);
    $stmt2->execute();

    $stmt3 = $conn->prepare("DELETE FROM lookingtoplaychat WHERE lookingtoplay_id = ?");
    $stmt3->bind_param("i", $lookingtoplay_id);
    $stmt3->execute();

    // Prepare the SQL statement to delete from lookingtoplay table
    $stmt4 = $conn->prepare("DELETE FROM lookingtoplay WHERE id = ?");
    $stmt4->bind_param("i", $lookingtoplay_id);
    $stmt4->execute();



    if ($stmt4->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "LookingToPlay entry deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete LookingToPlay entry or entry not found"]);
    }

    // Close the statements and connection
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
    $stmt4->close();
    closeConnection($conn);
}
?>