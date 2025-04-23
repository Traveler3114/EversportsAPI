<?php
require 'dompdf/autoload.inc.php';
require_once 'db.php';

use Dompdf\Dompdf;


// MakePDF function
function MakePDF(){
    // Open database connection
    $conn = openConnection();

    // Generate the table HTML
    $html = CreateTable("users", $conn);
    $html .= CreateTable("lookingtoplay", $conn);
    $html .= CreateTable("lookingtoplaychat", $conn);
    $html .= CreateTable("choosensport", $conn);
    $html .= CreateTable("availabledatetime", $conn);

    // Generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape'); // Landscape orientation
    $dompdf->render();

    echo $dompdf->output();

    // Close database connection
    $conn->close();
}

// CreateTable function (to fetch data and create HTML table)
function CreateTable($table, $conn){
    // Fetch data from the table
    $sql = "SELECT * FROM `$table`";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error in query: " . $conn->error);
    }

    // Start HTML
    $html = "<h2>Data from `$table`</h2>";
    $html .= "<table border='1' cellpadding='5' cellspacing='0'><tr>";

    // Get column names
    while ($field = $result->fetch_field()) {
        $html .= "<th>{$field->name}</th>";
    }
    $html .= "</tr>";

    // Add data rows
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>";
        foreach ($row as $value) {
            $html .= "<td>" . htmlspecialchars($value) . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";

    return $html;
}

?>
