<?php
function openConnection() {
    $conn = new mysqli("localhost", "apiuser", "PInterfaceHProgrammingPApplication40#", "eversportsdb");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
