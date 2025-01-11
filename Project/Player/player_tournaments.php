<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$dbname = "cricket";  // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tournaments for display
$sql = "SELECT * FROM tournaments ORDER BY start_date DESC";
$tournaments = $conn->query($sql);

// Check if the query failed
if ($tournaments === false) {
    die("SQL Query Error: " . $conn->error);
}

// Initialize $tournaments to an empty result set if it's null
if (!$tournaments) {
    $tournaments = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Tournaments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: #f9f9f9;
            color: #333;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background: linear-gradient(135deg, #1e3c72, #2a5298); /* Gradient background */
            color: #ffffff;
            height: 100vh;
            position: fixed;
            padding: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .logo {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 30px;
            color: #ffffff;
        }

        .sidebar .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar .nav-links li {
            margin-bottom: 15px;
        }

        .sidebar .nav-links a {
            display: flex;
            align-items: center;
            color: #ffffff;
            padding: 10px;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .sidebar .nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-links a i {
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .sidebar .user-profile {
            margin-top: auto;
            padding: 10px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar .user-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .sidebar .user-profile p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Main Content Styles */
        .content {
            margin-left: 300px;
            padding: 20px;
            width: 70%;
        }

        .content h1 {
            font-size: 2.5rem;
            color: #2c3e50; /* Dark blue */
            margin-bottom: 20px;
        }

        /* Tournament Cards */
        .tournament-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .tournament-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 300px;
            overflow: hidden;
        }

        .tournament-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .tournament-card .card-body {
            padding: 20px;
        }

        .tournament-card h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .tournament-card p {
            font-size: 1em;
            color: #666;
            margin-bottom: 10px;
        }

        .tournament-card .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .tournament-card .actions a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            color: #fff;
            font-size: 0.9em;
        }

        .tournament-card .actions .register-btn {
            background-color: #28a745; /* Green color for register button */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar a span {
                display: none;
            }

            .content {
                margin-left: 20px;
                width: calc(100% - 70px);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Logo -->
        <div class="logo">Player Panel</div>

        <!-- Navigation Links -->
        <ul class="nav-links">
            <li>
                <a href="player_dashboard.php">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="player_performance_analysis.php">
                    <i class="fas fa-chart-line"></i> Performance Analysis
                </a>
            </li>
            <li>
                <a href="player_monthly_report.php">
                    <i class="fas fa-file-alt"></i> Report
                </a>
            </li>
            <li>
                <a href="player_tournaments.php">
                    <i class="fas fa-trophy"></i> Tournaments
                </a>
            </li>
            <li>
                <a href="player_profile.php">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>

        <!-- User Profile -->
        <div class="user-profile">
            <p><?php echo $_SESSION['player_name']; ?></p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Tournaments</h1>

        <!-- Tournament Cards -->
        <div class="tournament-cards">
            <?php
            if ($tournaments && $tournaments->num_rows > 0) {
                while ($row = $tournaments->fetch_assoc()) {
                    $base_url = "http://localhost/Project/admin/"; // Remove "uploads/" from here
                    $image_path = $base_url . ($row['photo_url'] ? $row['photo_url'] : "uploads/default.jpg");

                    echo "<div class='tournament-card'>";
                    echo "<img src='" . $image_path . "' alt='Tournament Photo'>";
                    echo "<div class='card-body'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p><strong>Start Date:</strong> " . $row['start_date'] . "</p>";
                    echo "<p><strong>End Date:</strong> " . $row['end_date'] . "</p>";
                    echo "<p><strong>Location:</strong> " . $row['location'] . "</p>";
                    echo "<p><strong>Description:</strong> " . $row['description'] . "</p>";
                    echo "<div class='actions'>";
                    
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";

                    // Debugging: Print the image path
                    
                }
            } else {
                echo "<p>No tournaments found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>