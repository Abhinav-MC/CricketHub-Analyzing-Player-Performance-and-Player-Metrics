<?php
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

// Handle Delete Tournament
if (isset($_GET['delete'])) {
    $tournament_id = $_GET['delete'];
    $sql = "DELETE FROM tournaments WHERE id = $tournament_id";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Tournament deleted successfully!";
    } else {
        $error_message = "Error deleting tournament: " . $conn->error;
    }
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

        .tournament-card .actions .edit-btn {
            background-color: #007BFF;
        }

        .tournament-card .actions .delete-btn {
            background-color: #dc3545;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar content here -->

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
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Tournament List</h1>

        <!-- Success/Error Messages -->
        <?php if (!empty($success_message)) { ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if (!empty($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Tournament Cards -->
        <div class="tournament-cards">
            <?php
            if ($tournaments && $tournaments->num_rows > 0) {
                while ($row = $tournaments->fetch_assoc()) {
                    echo "<div class='tournament-card'>";
                    echo "<img src='" . ($row['photo_url'] ? $row['photo_url'] : "uploads/default.jpg") . "' alt='Tournament Photo'>";
                    echo "<div class='card-body'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p><strong>Start Date:</strong> " . $row['start_date'] . "</p>";
                    echo "<p><strong>End Date:</strong> " . $row['end_date'] . "</p>";
                    echo "<p><strong>Location:</strong> " . $row['location'] . "</p>";
                    echo "<p><strong>Description:</strong> " . $row['description'] . "</p>";
                    echo "<div class='actions'>";
                    echo "<a href='tournament_form.php?edit=" . $row['id'] . "' class='edit-btn'>Edit</a>";
                    echo "<a href='?delete=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this tournament?\")'>Delete</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No tournaments found.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>