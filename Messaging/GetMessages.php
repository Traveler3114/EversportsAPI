<?php
require_once 'JWToken.php';
require_once 'db.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$jwt = $input['jwt'] ?? null;
$lookingtoplay_id = $input['lookingtoplay_id'] ?? null;

$conn = openConnection();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT sender_id, encrypted_message FROM lookingtoplaychat WHERE lookingtoplay_id = ?");
$stmt->bind_param("i", $lookingtoplay_id);
$stmt->execute();
$result = $stmt->get_result();

$xml = new SimpleXMLElement('<root/>');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $item = $xml->addChild('item');
        foreach ($row as $key => $value) {
            $item->addChild($key, htmlspecialchars($value));
        }
    }
    echo json_encode(["status" => "success", "obj" => $xml->asXML()]);
} else {
    echo json_encode(["status" => "error", "message" => "No messages found"]);
}

$stmt->close();
$conn->close();

?>
