<?php
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

// Get player ID (you can fetch this from session or URL)
session_start();
if (!isset($_SESSION['player_id'])) {
    die("Player not logged in.");
}
$player_id = $_SESSION['player_id'];

// Function to calculate monthly report for a player
function getPlayerMonthlyReport($conn, $player_id, $year) {
    // Fetch player performance for each month of the selected year
    $sql = "SELECT 
                MONTH(match_date) AS month,
                SUM(runs_scored) AS total_runs,
                SUM(wickets_taken) AS total_wickets
            FROM match_performance 
            WHERE player_id = ? 
            AND YEAR(match_date) = ?
            GROUP BY MONTH(match_date)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $player_id, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

// Check if the form is submitted
if (isset($_GET['year'])) {
    $year = $_GET['year'];

    $report = getPlayerMonthlyReport($conn, $player_id, $year);

    // Prepare data for charts
    $months = [];
    $runs_data = [];
    $wickets_data = [];

    while ($row = $report->fetch_assoc()) {
        $months[] = date("F", mktime(0, 0, 0, $row['month'], 1)); // Convert month number to month name
        $runs_data[] = $row['total_runs'];
        $wickets_data[] = $row['total_wickets'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Monthly Performance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            margin-left: 270px;
            padding: 20px;
        }

        .content h1 {
            font-size: 2.5rem;
            color: #2c3e50; /* Dark blue */
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        
        select {
            padding: 10px;
            border: 2px solid #007BFF;
            border-radius: 8px;
            background-color: #ffffff;
            font-size: 16px;
            color: #333;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            appearance: none; /* Remove default arrow */
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23007BFF"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
        }

        select:hover {
            border-color: #0056b3;
        }

        select:focus {
            border-color: #007BFF;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        /* Enhanced Button */
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }
        .chart-container {
            width: 100%;
            margin-top: 20px;
        }

        .no-data {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 20px;
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
                <a href="player_profile.php">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
            <li>
                <a href="player_tournaments.php">
                    <i class="fas fa-trophy"></i> Tournaments
                </a>
            </li>
            <li>
                <a href="player_monthly_report.php">
                    <i class="fas fa-file-alt"></i> Monthly Report
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
            <img src="https://via.placeholder.com/50" alt="Player Photo">
            <p><?php echo $_SESSION['player_name']; ?></p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Player Monthly Performance</h1>
        <form action="player_monthly_report.php" method="GET">
            <label for="year">Select Year:</label>
            <select name="year" id="year" required>
                <option value="2023" <?php if (isset($year) && $year == 2023) echo 'selected'; ?>>2023</option>
                <option value="2024" <?php if (isset($year) && $year == 2024) echo 'selected'; ?>>2024</option>
                <option value="2025" <?php if (isset($year) && $year == 2025) echo 'selected'; ?>>2025</option>
            </select>

            <button type="submit">Generate Report</button>
        </form>

        <?php if (isset($report)) { ?>
            <h2>Monthly Performance for <?php echo $year; ?></h2>

            <?php if ($report->num_rows > 0) { ?>
                <!-- Column Chart for Runs Scored -->
                <h3>Runs Scored (Column Chart)</h3>
                <div class="chart-container">
                    <canvas id="runsChart"></canvas>
                </div>

                <!-- Column Chart for Wickets Taken -->
                <h3>Wickets Taken (Column Chart)</h3>
                <div class="chart-container">
                    <canvas id="wicketsChart"></canvas>
                </div>

                <script>
                    // Runs Scored Column Chart
                    var runsChart = new Chart(document.getElementById('runsChart'), {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($months); ?>,
                            datasets: [{
                                label: 'Runs Scored',
                                data: <?php echo json_encode($runs_data); ?>,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Wickets Taken Column Chart
                    var wicketsChart = new Chart(document.getElementById('wicketsChart'), {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($months); ?>,
                            datasets: [{
                                label: 'Wickets Taken',
                                data: <?php echo json_encode($wickets_data); ?>,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
            <?php } else { ?>
                <!-- No Data Message -->
                <div class="no-data">
                    <p>No performance data found for the selected year.</p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>