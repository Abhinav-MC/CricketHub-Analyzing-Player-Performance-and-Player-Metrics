<?php
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required fields are set
    if (isset($_POST['team1'], $_POST['team2'], $_POST['match_date'], $_POST['match_type'], $_POST['match_result'], $_POST['team1_score'], $_POST['team2_score'])) {
        // Get form data
        $team1 = $_POST['team1'];
        $team2 = $_POST['team2'];
        $match_date = $_POST['match_date'];
        $match_type = $_POST['match_type'];
        $match_result = $_POST['match_result'];

        // Get team scores (ensure they are set and not empty)
        $team1_score = $_POST['team1_score'];
        $team2_score = $_POST['team2_score'];

        // Additional validation: Ensure scores are numeric
        if (!is_numeric($team1_score) || !is_numeric($team2_score)) {
            $error_message = "Team scores must be numeric.";
        } else {
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

            // Insert data into the database
            $sql = "INSERT INTO match_details (team1, team2, match_date, match_type, match_result, team1_score, team2_score)
                    VALUES ('$team1', '$team2', '$match_date', '$match_type', '$match_result', '$team1_score', '$team2_score')";

           

            if ($conn->query($sql) === TRUE) {
                // Success message
                $success_message = "Match details saved successfully!";
            } else {
                // Error message
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
    } else {
        // Required fields are missing
        $error_message = "Required fields are missing. Please check the form submission.";
    }
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

        .card {
            border: none;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            text-align: center;
            color: white;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }

        .card h5 {
            font-size: 1rem;
        }

        .card h2 {
            font-size: 1.5rem;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .row > .col-md-3 {
            flex: 0 0 23%;
            max-width: 23%;
        }

        @media (max-width: 768px) {
            .row > .col-md-3 {
                flex: 0 0 48%;
                max-width: 48%;
            }
        }

        @media (max-width: 576px) {
            .row > .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
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

        .form-container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .form-container h3 {
            margin-bottom: 20px;
            font-size: 1.5em;
            text-align: center;
            color: #34495e;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 1em;
        }

        .form-group button {
            background-color: red;
            color: #fff;
            padding: 20px 75px;
            border: none;
            border-radius: 20px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 330px;
        }

        .form-group button:hover {
            background-color: #2c3e50;
        }

        .success-message {
            background-color: #d4edda; /* Green background */
            color: #155724; /* Dark green text */
            padding: 15px;
            border: 1px solid #c3e6cb; /* Light green border */
            border-radius: 5px;
            margin-top: 20px; /* Add margin to separate it from the submit button */
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            display: block; /* Ensure the message is visible */
        }

        .error-message {
            background-color: #f8d7da; /* Red background */
            color: #721c24; /* Dark red text */
            padding: 15px;
            border: 1px solid #f5c6cb; /* Light red border */
            border-radius: 5px;
            margin-top: 20px; /* Add margin to separate it from the submit button */
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            display: block; /* Ensure the message is visible */
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
    <div class="content">
        <h1>Match Details Form</h1>
        <form action="match_details.php" method="POST" class="form-container" >
            <div class="form-group">
                <label for="team1">Team 1:</label>
                <input type="text" name="team1" id="team1" required>
            </div>

            <div class="form-group">
                <label for="team2">Team 2:</label>
                <input type="text" name="team2" id="team2" required>
            </div>

            <div class="form-group">
                <label for="match_date">Match Date:</label>
                <input type="date" name="match_date" id="match_date" required>
            </div>

            <div class="form-group">
                <label for="team1_score">Team1 Score:</label>
                <input type="number" name="team1_score" id="team1_score" required>
            </div>

            <div class="form-group">
                <label for="team2_score">Team2 Score:</label>
                <input type="number" name="team2_score" id="team2_score" required>
            </div>

            <div class="form-group">
                <label for="match_type">Match Type:</label>
                <select name="match_type" id="match_type" required>
                    <option value="20 Over">20 Over</option>
                    <option value="50 Over">50 Over</option>
                    <option value="90 Over">90 Over</option>
                </select>
            </div>

            <div class="form-group">
                <label for="match_result">Match Result:</label>
                <input type="text" name="match_result" id="match_result" required>
            </div>

            <div class="form-group">
                <button type="submit">Submit</button>
            </div>

            <!-- Success Message Placement -->
            <?php if (isset($success_message)) { ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php } ?>

            <!-- Error Message Placement -->
            <?php if (isset($error_message)) { ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php } ?>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>