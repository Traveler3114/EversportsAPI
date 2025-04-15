<?php
require_once 'JWToken.php';
require_once 'db.php';

function GetAllMessages($lookingtoplay_id){
    $conn = openConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT* FROM lookingtoplaychat (sender_id, encrypted_message)  WHERE lookingtoplay_id = ?");
    $stmt->bind_param("i", $lookingtoplay_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $xml = new SimpleXMLElement('<root/>');
    if ($result->num_rows > 0) {
        // Loop through each row from the "lookingtoplay" table
        while ($row = $result->fetch_assoc()) {
            $item = $xml->addChild('item');
            foreach ($row as $key => $value) {
                $item->addChild($key, htmlspecialchars($value));
            }
        }
        echo json_encode(["status" => "error", "obj" => $xml->asXML()]);
    } else {
        echo json_encode(["status" => "error", "message" => "No messages found"]);
    }
    $stmt->close();

}

?>