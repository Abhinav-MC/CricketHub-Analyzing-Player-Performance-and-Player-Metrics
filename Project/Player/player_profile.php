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

// Fetch player profile details for the logged-in player
$sql = "SELECT * FROM player_registration WHERE player_id = $player_id";
$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

$player_profile = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Profile</title>
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

        /* Profile Section */
        .profile-section {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Profile Photo (Rounded Shape) */
        .profile-photo img {
            width: 150px; /* Adjust size as needed */
            height: 150px; /* Adjust size as needed */
            object-fit: cover;
            border: 3px solid #2c3e50; /* Optional border */
            border-radius: 50%; /* Makes the image round */
        }

        .profile-details {
            margin-top: 20px;
            width: 100%;
        }

        .profile-details p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .profile-details strong {
            color: #2c3e50;
            font-weight: 600;
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

            <p><?php echo $player_profile['full_name']; ?></p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Player Profile</h1>
        <div class="profile-section">
            <div class="profile-photo">
                <!-- Display the player's photo directly fetched from the database -->
                <?php
                // Check if the photo_url is set and not empty
                if (!empty($player_profile['photo_url'])) {
                    echo '<img src="/Project/admin/' . $player_profile['photo_url'] . '" alt="' . $player_profile['full_name'] . '">';
                } else {
                    // Display a default image if no photo is available
                    echo '<img src="default_profile.png" alt="Default Profile Image">';
                }
                ?>
            </div>
            <div class="profile-details">
                <p><strong>Full Name:</strong> <?php echo $player_profile['full_name']; ?></p>
                <p><strong>Username:</strong> <?php echo $player_profile['username']; ?></p>
                <p><strong>Age:</strong> <?php echo $player_profile['age']; ?></p>
                <p><strong>City:</strong> <?php echo $player_profile['city']; ?></p>
                <p><strong>Gender:</strong> <?php echo $player_profile['gender']; ?></p>
                <p><strong>Phone Number:</strong> <?php echo $player_profile['phone_number']; ?></p>
                <p><strong>Email:</strong> <?php echo $player_profile['email']; ?></p>
                <p><strong>Player Role:</strong> <?php echo $player_profile['player_role']; ?></p>
                <p><strong>Batting Style:</strong> <?php echo $player_profile['batting_style']; ?></p>
                <p><strong>Bowling Style:</strong> <?php echo $player_profile['bowling_style']; ?></p>
                <p><strong>Experience Level:</strong> <?php echo $player_profile['experience_level']; ?></p>
                <p><strong>Team Name:</strong> <?php echo $player_profile['team_name']; ?></p>
                <p><strong>Achievements:</strong> <?php echo $player_profile['achievements']; ?></p>
            </div>
        </div>
    </div>
</body>
</html>