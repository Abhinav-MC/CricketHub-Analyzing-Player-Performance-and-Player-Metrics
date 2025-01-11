<?php
session_start();

// Check if the player is logged in
if (!isset($_SESSION['player_id'])) {
    header("Location: login.php");
    exit();
}

$player_id = $_SESSION['player_id'];
$player_name = $_SESSION['player_name'];

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

// Function to calculate batting metrics
function calculateBattingMetrics($player_id, $conn) {
    $sql = "SELECT 
                SUM(runs_scored) AS total_runs,
                SUM(balls_faced) AS total_balls_faced,
                SUM(fours) AS total_fours,
                SUM(sixes) AS total_sixes,
                COUNT(*) AS total_matches,
                SUM(CASE WHEN runs_scored >= 50 AND runs_scored < 100 THEN 1 ELSE 0 END) AS half_centuries,
                SUM(CASE WHEN runs_scored >= 100 THEN 1 ELSE 0 END) AS centuries,
                COUNT(CASE WHEN runs_scored IS NOT NULL THEN 1 END) AS dismissals
            FROM match_performance
            WHERE player_id = $player_id";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error in SQL query: " . $conn->error);
    }

    $row = $result->fetch_assoc();

    // Set default values for missing keys
    $batting_metrics = [
        'total_runs' => $row['total_runs'] ?? 0,
        'total_balls_faced' => $row['total_balls_faced'] ?? 0,
        'total_fours' => $row['total_fours'] ?? 0,
        'total_sixes' => $row['total_sixes'] ?? 0,
        'total_matches' => $row['total_matches'] ?? 0,
        'half_centuries' => $row['half_centuries'] ?? 0,
        'centuries' => $row['centuries'] ?? 0,
        'dismissals' => $row['dismissals'] ?? 0
    ];

    // Calculate additional metrics
    $batting_metrics['strike_rate'] = ($batting_metrics['total_balls_faced'] > 0) ? ($batting_metrics['total_runs'] / $batting_metrics['total_balls_faced']) * 100 : 0;
    $batting_metrics['batting_average'] = ($batting_metrics['dismissals'] > 0) ? $batting_metrics['total_runs'] / $batting_metrics['dismissals'] : 0;

    return $batting_metrics;
}

// Function to calculate bowling metrics
function calculateBowlingMetrics($player_id, $conn) {
    $sql = "SELECT 
                SUM(wickets_taken) AS total_wickets,
                SUM(overs_bowled) AS total_overs_bowled,
                SUM(runs_conceded) AS total_runs_conceded,
                MIN(runs_conceded) AS best_bowling_figures
            FROM match_performance
            WHERE player_id = $player_id";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error in SQL query: " . $conn->error);
    }

    $row = $result->fetch_assoc();

    // Set default values for missing keys
    $bowling_metrics = [
        'total_wickets' => $row['total_wickets'] ?? 0,
        'total_overs_bowled' => $row['total_overs_bowled'] ?? 0,
        'total_runs_conceded' => $row['total_runs_conceded'] ?? 0,
        'best_bowling_figures' => $row['best_bowling_figures'] ?? 0
    ];

    // Calculate additional metrics
    $bowling_metrics['economy_rate'] = ($bowling_metrics['total_overs_bowled'] > 0) ? ($bowling_metrics['total_runs_conceded'] / $bowling_metrics['total_overs_bowled']) : 0;
    $bowling_metrics['bowling_average'] = ($bowling_metrics['total_wickets'] > 0) ? $bowling_metrics['total_runs_conceded'] / $bowling_metrics['total_wickets'] : 0;

    return $bowling_metrics;
}

// Function to calculate fielding metrics
function calculateFieldingMetrics($player_id, $conn) {
    $sql = "SELECT 
                SUM(catches) AS total_catches,
                SUM(stumpings) AS total_stumpings,
                SUM(run_outs) AS total_run_outs
            FROM match_performance
            WHERE player_id = $player_id";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error in SQL query: " . $conn->error);
    }

    $row = $result->fetch_assoc();

    // Set default values for missing keys
    $fielding_metrics = [
        'total_catches' => $row['total_catches'] ?? 0,
        'total_stumpings' => $row['total_stumpings'] ?? 0,
        'total_run_outs' => $row['total_run_outs'] ?? 0
    ];

    return $fielding_metrics;
}

// Calculate metrics for the logged-in player
$batting_metrics = calculateBattingMetrics($player_id, $conn);
$bowling_metrics = calculateBowlingMetrics($player_id, $conn);
$fielding_metrics = calculateFieldingMetrics($player_id, $conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9; /* Light background */
            color: #333; /* Dark text color */
        }

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

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #2c3e50; /* Dark blue */
        }

        .header .user-info {
            font-size: 1.2rem;
            color: #777;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .metric-card {
            background: #ffffff; /* White background */
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        }

        .metric-card h3 {
            font-size: 1.2rem;
            color: #777;
            margin-bottom: 10px;
        }

        .metric-card h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50; /* Dark blue */
        }

        .metric-card.matches { border-bottom: 5px solid #3498db; } /* Blue */
        .metric-card.runs { border-bottom: 5px solid #2ecc71; } /* Green */
        .metric-card.wickets { border-bottom: 5px solid #e74c3c; } /* Red */
        .metric-card.catches { border-bottom: 5px solid #f39c12; } /* Orange */
        .metric-card.batting-avg { border-bottom: 5px solid #9b59b6; } /* Purple */
        .metric-card.bowling-avg { border-bottom: 5px solid #1abc9c; } /* Teal */

        .charts-section {
            margin-top: 40px;
        }

        .charts-section h2 {
            font-size: 2rem;
            color: #2c3e50; /* Dark blue */
            margin-bottom: 20px;
        }

        canvas {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            background: #ffffff; /* White background */
        }
    </style>
    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <i class="fas fa-user"></i> Report
                </a>
            </li>
            <li>
                <a href="player_tournaments.php">
                    <i class="fas fa-trophy"></i> Tournaments
                </a>
            </li>
            <li>
                <a href="player_profile.php">
                    <i class="fas fa-file-alt"></i> Profile
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
            <p><?php echo $player_name; ?></p>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <h1>Welcome, <?php echo $player_name; ?></h1>
            <div class="user-info">Player ID: <?php echo $player_id; ?></div>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card matches">
                <h3>Total Matches</h3>
                <h2><?php echo $batting_metrics['total_matches']; ?></h2>
            </div>
            <div class="metric-card runs">
                <h3>Total Runs</h3>
                <h2><?php echo $batting_metrics['total_runs']; ?></h2>
            </div>
            <div class="metric-card wickets">
                <h3>Total Wickets</h3>
                <h2><?php echo $bowling_metrics['total_wickets']; ?></h2>
            </div>
            <div class="metric-card catches">
                <h3>Total Catches</h3>
                <h2><?php echo $fielding_metrics['total_catches']; ?></h2>
            </div>
            <div class="metric-card batting-avg">
                <h3>Batting Average</h3>
                <h2><?php echo number_format($batting_metrics['batting_average'], 2); ?></h2>
            </div>
            <div class="metric-card bowling-avg">
                <h3>Bowling Average</h3>
                <h2><?php echo number_format($bowling_metrics['bowling_average'], 2); ?></h2>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <h2>Performance Charts</h2>
            <div>
                <h3>4s and 6s</h3>
                <canvas id="foursSixesChart"></canvas>
            </div>
            <div>
                <h3>50s and 100s</h3>
                <canvas id="fiftiesCenturiesChart"></canvas>
            </div>
            <div>
                <h3>Catches, Stumpings, and Run Outs</h3>
                <canvas id="fieldingChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Batting Metrics Data (4s and 6s)
        const foursSixesData = {
            labels: ['4s', '6s'],
            datasets: [{
                label: '4s and 6s',
                data: [
                    <?php echo $batting_metrics['total_fours']; ?>,
                    <?php echo $batting_metrics['total_sixes']; ?>
                ],
                backgroundColor: ['#3498db', '#e74c3c'], /* Blue and Red */
                borderColor: ['#3498db', '#e74c3c'],
                borderWidth: 1
            }]
        };

        // Batting Metrics Data (50s and 100s)
        const fiftiesCenturiesData = {
            labels: ['50s', '100s'],
            datasets: [{
                label: '50s and 100s',
                data: [
                    <?php echo $batting_metrics['half_centuries']; ?>,
                    <?php echo $batting_metrics['centuries']; ?>
                ],
                backgroundColor: ['#f39c12', '#9b59b6'], /* Orange and Purple */
                borderColor: ['#f39c12', '#9b59b6'],
                borderWidth: 1
            }]
        };

        // Fielding Metrics Data (Catches, Stumpings, Run Outs)
        const fieldingData = {
            labels: ['Catches', 'Stumpings', 'Run Outs'],
            datasets: [{
                label: 'Fielding Metrics',
                data: [
                    <?php echo $fielding_metrics['total_catches']; ?>,
                    <?php echo $fielding_metrics['total_stumpings']; ?>,
                    <?php echo $fielding_metrics['total_run_outs']; ?>
                ],
                backgroundColor: ['#2ecc71', '#1abc9c', '#e67e22'], /* Green, Teal, and Orange */
                borderColor: ['#2ecc71', '#1abc9c', '#e67e22'],
                borderWidth: 1
            }]
        };

        // Configuration for bar charts
        const config = {
            type: 'bar',
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Performance Metrics'
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        };

        // Render the charts
        const foursSixesChart = new Chart(
            document.getElementById('foursSixesChart'),
            {
                ...config,
                data: foursSixesData
            }
        );

        const fiftiesCenturiesChart = new Chart(
            document.getElementById('fiftiesCenturiesChart'),
            {
                ...config,
                data: fiftiesCenturiesData
            }
        );

        const fieldingChart = new Chart(
            document.getElementById('fieldingChart'),
            {
                ...config,
                data: fieldingData
            }
        );
    </script>
</body>
</html>