<?php
session_start();

// Check if the player is logged in
if (!isset($_SESSION['player_id'])) {
    header("Location: login.php");
    exit();
}

$player_id = $_SESSION['player_id'];

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

// Handle tournament registration
if (isset($_GET['id'])) {
    $tournament_id = $_GET['id'];

    // Check if the player is already registered for the tournament
    $check_sql = "SELECT * FROM tournament_registrations WHERE player_id = $player_id AND tournament_id = $tournament_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "You are already registered for this tournament.";
    } else {
        // Insert registration into the database
        $insert_sql = "INSERT INTO tournament_registrations (player_id, tournament_id) VALUES ($player_id, $tournament_id)";
        if ($conn->query($insert_sql) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>