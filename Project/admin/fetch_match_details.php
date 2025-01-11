<?php
// fetch_match_details.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cricket";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if match_id is provided
if (isset($_GET['match_id'])) {
    $match_id = $_GET['match_id'];

    // Fetch match details from the database
    $sql = "SELECT match_date, match_type FROM match_details WHERE match_id = '$match_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Match found, return details as JSON
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        // No match found, return an error message
        echo json_encode(array('error' => 'No match found with the given ID.'));
    }
} else {
    // No match_id provided, return an error message
    echo json_encode(array('error' => 'Match ID is required.'));
}

$conn->close();
?>