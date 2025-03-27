<?php
require_once 'db.php';

function GetAllLookingToPay(){
    $conn = openConnection(); // Assuming openConnection() returns a valid MySQLi connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the first query for "lookingtoplay"
    $stmt = $conn->prepare("SELECT * FROM lookingtoplay");
    $stmt->execute();
    $result = $stmt->get_result();

    // Create the root of the XML document
    $xml = new SimpleXMLElement('<root/>');

    // Check if there are results
    if ($result->num_rows > 0) {
        // Loop through each row from the "lookingtoplay" table
        while ($row = $result->fetch_assoc()) {
            $item = $xml->addChild('item');
            foreach ($row as $key => $value) {
                $item->addChild($key, htmlspecialchars($value));
            }
            // Query for availabledatetimes related to the current lookingtoplay entry
            $stmt2 = $conn->prepare("SELECT * FROM availabledatetime WHERE lookingtoplay_id = ?");
            $stmt2->bind_param("i", $row['id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $item2 = $item->addChild('availabledatetimes');

            // Loop through the availabledatetimes result and add it to the XML
            while ($row2 = $result2->fetch_assoc()) {
                $item3 = $item2->addChild('availabledatetime');
                foreach ($row2 as $key => $value) {
                    $item3->addChild($key, htmlspecialchars($value));
                }
            }
            // Query for choosensport related to the current lookingtoplay entry
            $stmt3 = $conn->prepare("SELECT * FROM choosensport WHERE lookingtoplay_id = ?");
            $stmt3->bind_param("i", $row['id']);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            $item2 = $item->addChild('choosenSports');

            // Loop through the choosensport result and add it to the XML
            while ($row3 = $result3->fetch_assoc()) {
                $item3 = $item2->addChild('sport');
                foreach ($row3 as $key => $value) {
                    $item3->addChild($key, htmlspecialchars($value));
                }
            }
        }
    }

    // Close all prepared statements and the database connection
    $stmt->close();
    $stmt2->close();
    $stmt3->close();
    $conn->close();

    echo $xml->asXML();
}
?>
