<?php
// Fetch all registered players
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cricket";  // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM player_registration";
$result = $conn->query($sql);

$conn->close();

// Handle success and error messages
$success_message = "";
$error_message = "";

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "Player deleted successfully!";
}

if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error_message = "Error deleting player. Please try again.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CricketHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #141E30, #243B55);
            font-family: 'Poppins', sans-serif;
            color: #FFFFFF;
        }

        .sidebar {
            background: #1B2735;
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 1.1rem;
            color: #FFFFFF;
            display: block;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background: #2DE1FC;
        }

        .sidebar .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }

        .sidebar .dropdown-menu {
            background: #1B2735;
            border: none;
        }

        .sidebar .dropdown-menu a {
            padding: 10px 20px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            background-color: #1B2735;
            padding: 15px;
            border-bottom: 2px solid #2DE1FC;
        }

        .navbar h1 {
            color: #2DE1FC;
            margin: 0;
            font-size: 1.8rem;
        }

        .player-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .player-card {
            background: linear-gradient(135deg, #ffffff, #f0f8ff);
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .player-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .player-photo img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .player-details {
            padding: 15px;
            flex-grow: 1;
        }

        .player-details p {
            margin: 8px 0;
            font-size: 14px;
            color: #555;
        }

        .player-details strong {
            color: #2c3e50;
            font-weight: 600;
        }

        .actions {
            margin-top: 15px;
            text-align: right;
            padding: 10px;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
        }

        .actions a {
            text-decoration: none;
            color: #007bff;
            margin-left: 10px;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .actions a:hover {
            color: #e74c3c;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .logo span {
                display: none;
            }

            .sidebar a span,
            .sidebar .dropdown h3 span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }

            .player-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-user-friends"></i> Players</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="Player_register.php"></i> Add Player</a>
                <a class="dropdown-item" href="total_players.php"></i> View Players</a>
                <a class="dropdown-item" href="edit_players.php"></i> Manage Players</a>
                <a class="dropdown-item" href="manage_players.php"></i> Manage Request</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-chart-line"></i> Player Performance</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="player_performance_metrics.php"></i> Player Performance Metrics</a>
                <a class="dropdown-item" href="player_performance_analysis.php"></i> Player Performance Analysis</a>
                <a class="dropdown-item" href="match_performance.php"></i> Match Performance</a>
                <a class="dropdown-item" href="match_details.php"></i> Match Details</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-user-graduate"></i> Trainee</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="trainee_registration.php"></i> Add Trainee</a>
                <a class="dropdown-item" href="edit_trainee.php"></i> Manage Trainee</a>
                <a class="dropdown-item" href="display_trainees.php"></i> View Trainee</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-trophy"></i> Tournaments</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="tournament_details.php"></i> Add Tournament</a>
                <a class="dropdown-item" href="tournament_list.php"></i> View Tournaments</a>
            </div>
        </div>

        <a href="weekly_report.php"><i class="fas fa-file-alt"></i> Report</a>

        <a href="enquiries.php"><i class="fas fa-user-plus"></i> Enquiries</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
   
    <div class="main-content">
        <h1>Registered Players</h1>

        <!-- Display success or error messages -->
        <?php if (!empty($success_message)) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php } ?>

        <?php if (!empty($error_message)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php } ?>

        <div class="player-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='player-card'>";
                    echo "<div class='player-photo'>";
                    echo "<img src='{$row['photo_url']}' alt='{$row['full_name']}'>";
                    echo "</div>";
                    echo "<div class='player-details'>";
                    echo "<p><strong>ID:</strong> {$row['player_id']}</p>";
                    echo "<p><strong>Full Name:</strong> {$row['full_name']}</p>";
                    echo "<p><strong>Username:</strong> {$row['username']}</p>";
                    echo "<p><strong>Age:</strong> {$row['age']}</p>";
                    echo "<p><strong>City:</strong> {$row['city']}</p>";
                    echo "<p><strong>Gender:</strong> {$row['gender']}</p>";
                    echo "<p><strong>Phone Number:</strong> {$row['phone_number']}</p>";
                    echo "<p><strong>Email:</strong> {$row['email']}</p>";
                    echo "<p><strong>Player Role:</strong> {$row['player_role']}</p>";
                    echo "<p><strong>Batting Style:</strong> {$row['batting_style']}</p>";
                    echo "<p><strong>Bowling Style:</strong> {$row['bowling_style']}</p>";
                    echo "<p><strong>Experience Level:</strong> {$row['experience_level']}</p>";
                    echo "<p><strong>Team Name:</strong> {$row['team_name']}</p>";
                    echo "<p><strong>Achievements:</strong> {$row['achievements']}</p>";
                    echo "<div class='actions'>";
                    echo "<a href='edit_players.php?id={$row['player_id']}'>Edit</a> | ";
                    echo "<a href='delete_player.php?id={$row['player_id']}' onclick='return confirm(\"Are you sure you want to delete this player?\")'>Delete</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No players registered yet.</p>";
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>