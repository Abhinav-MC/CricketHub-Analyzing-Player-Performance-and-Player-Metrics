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

// Fetch trainees from the database
$sql = "SELECT * FROM trainees";
$result = $conn->query($sql);

$conn->close();
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

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .trainee-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .trainee-card {
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

        .trainee-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .trainee-photo img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .trainee-details {
            padding: 15px;
            flex-grow: 1;
        }

        .trainee-details p {
            margin: 8px 0;
            font-size: 14px;
            color: #555;
        }

        .trainee-details strong {
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

            .content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }

            .trainee-card {
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
                <a class="dropdown-item" href="Player_register.php">Add Player</a>
                <a class="dropdown-item" href="total_players.php">View Players</a>
                <a class="dropdown-item" href="edit_players.php">Manage Players</a>
                <a class="dropdown-item" href="manage_players.php">Manage Request</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-chart-line"></i> Player Performance</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="player_performance_metrics.php">Player Performance Metrics</a>
                <a class="dropdown-item" href="player_performance_analysis.php">Player Performance Analysis</a>
                <a class="dropdown-item" href="match_performance.php">Match Performance</a>
                <a class="dropdown-item" href="match_details.php">Match Details</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-user-graduate"></i> Trainee</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="trainee_registration.php">Add Trainee</a>
                <a class="dropdown-item" href="edit_trainee.php">Manage Trainee</a>
                <a class="dropdown-item" href="display_trainees.php">View Trainee</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-trophy"></i> Tournaments</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="tournament_details.php">Add Tournament</a>
                <a class="dropdown-item" href="tournament_list.php">View Tournaments</a>
            </div>
        </div>

        <a href="weekly_report.php"><i class="fas fa-file-alt"></i> Report</a>

        <a href="enquiries.php"><i class="fas fa-user-plus"></i> Enquiries</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Registered Trainees</h1>
        <div class="trainee-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='trainee-card'>";
                    echo "<div class='trainee-photo'>";
                    echo "<img src='{$row['photo']}' alt='{$row['name']}'>";
                    echo "</div>";
                    echo "<div class='trainee-details'>";
                    echo "<p><strong>Name:</strong> {$row['name']}</p>";
                    echo "<p><strong>Age:</strong> {$row['age']}</p>";
                    echo "<p><strong>Gender:</strong> {$row['gender']}</p>";
                    echo "<p><strong>Email:</strong> {$row['email']}</p>";
                    echo "<p><strong>Phone:</strong> {$row['phone_number']}</p>";
                    echo "<p><strong>Role:</strong> {$row['role']}</p>";
                    echo "<p><strong>Batting Style:</strong> {$row['batting_style']}</p>";
                    echo "<p><strong>Bowling Style:</strong> {$row['bowling_style']}</p>";
                    echo "<p><strong>Experience Level:</strong> {$row['experience_level']}</p>";
                    echo "<p><strong>Achievements:</strong> {$row['achievements']}</p>";
                    echo "<div class='actions'>";
                    echo "<a href='edit_trainee.php?id={$row['id']}'>Edit</a> | ";
                    echo "<a href='delete_trainee.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this trainee?\")'>Delete</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No trainees registered yet.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>