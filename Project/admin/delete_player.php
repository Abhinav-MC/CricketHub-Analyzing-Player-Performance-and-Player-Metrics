<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cricket"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the player ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $player_id = $_GET['id'];

    // Delete the player from the player_registration table
    $sql = "DELETE FROM player_registration WHERE player_id = '$player_id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the view_players.php page with a success message
        header("Location: total_players.php?success=1");
        exit();
    } else {
        // Redirect back to the view_players.php page with an error message
        header("Location: total_players.php?error=1");
        exit();
    }
} else {
    // If no player ID is provided, redirect back to the view_players.php page
    header("Location: total_players.php?error=1");
    exit();
}

$conn->close();
?>