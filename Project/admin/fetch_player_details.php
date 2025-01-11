<?php
// fetch_player_details.php

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

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Fetch player details from the database
    $sql = "SELECT id, name, city, phone FROM players WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Player found, return details as JSON
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        // No player found, return an error message
        echo json_encode(array('error' => 'No player found with the given username.'));
    }
} else {
    // No username provided, return an error message
    echo json_encode(array('error' => 'Username is required.'));
}

$conn->close();
?>