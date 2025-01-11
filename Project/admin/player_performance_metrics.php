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

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        select, button {
            padding: 8px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: black;
        }

        th {
            background-color: #4e54c8;
            color: white;
        }

        tr:nth-child(even) {
            background-color: white;
        }

        tr:hover {
            background-color: white;
        }

        .export-buttons {
            margin-top: 20px;
        }

        .export-buttons button {
            margin-right: 10px;
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

            table {
                font-size: 14px;
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
        <h1>Player Performance Metrics</h1>
        <form action="player_performance_metrics.php" method="GET">
            <label for="player_id">Select Player:</label>
            <select name="player_id" id="player_id" required>
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

                // Fetch all players from the database
                $sql = "SELECT id, name FROM players";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Check if the current player is the selected one
                        $selected = (isset($_GET['player_id']) && $_GET['player_id'] == $row['id']) ? 'selected' : '';
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No players found</option>";
                }

                $conn->close();
                ?>
            </select>

            <label for="format">Select Format:</label>
            <select name="format" id="format" required>
                <?php
                $formats = ["All", "20", "50", "90"];
                foreach ($formats as $formatOption) {
                    $selected = (isset($_GET['format']) && $_GET['format'] == $formatOption) ? 'selected' : '';
                    echo "<option value='$formatOption' $selected>$formatOption Over</option>";
                }
                ?>
            </select>

            <button type="submit">View Metrics</button>
        </form>

        <?php
        // Check if the form is submitted
        if (isset($_GET['player_id']) && isset($_GET['format'])) {
            $player_id = $_GET['player_id'];
            $format = $_GET['format'];

            // Database connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Function to calculate batting metrics
            function calculateBattingMetrics($player_id, $conn, $format) {
                $match_type = ($format == "All") ? "" : "$format over";
                $formatCondition = ($format == "All") ? "" : " AND match_type = '$match_type'";
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
                        WHERE player_id = $player_id $formatCondition";

                $result = $conn->query($sql);

                // Check if the query failed
                if (!$result) {
                    die("Error in SQL query: " . $conn->error);
                }

                $row = $result->fetch_assoc();

                $batting_metrics = [
                    'total_runs' => $row['total_runs'],
                    'total_balls_faced' => $row['total_balls_faced'],
                    'strike_rate' => ($row['total_balls_faced'] > 0) ? ($row['total_runs'] / $row['total_balls_faced']) * 100 : 0,
                    'total_fours' => $row['total_fours'],
                    'total_sixes' => $row['total_sixes'],
                    'half_centuries' => $row['half_centuries'],
                    'centuries' => $row['centuries'],
                    'batting_average' => ($row['dismissals'] > 0) ? $row['total_runs'] / $row['dismissals'] : 0
                ];

                return $batting_metrics;
            }

            // Function to calculate bowling metrics
            function calculateBowlingMetrics($player_id, $conn, $format) {
                $match_type = ($format == "All") ? "" : "$format over";
                $formatCondition = ($format == "All") ? "" : " AND match_type = '$match_type'";
                $sql = "SELECT 
                            SUM(wickets_taken) AS total_wickets,
                            SUM(overs_bowled) AS total_overs_bowled,
                            SUM(runs_conceded) AS total_runs_conceded,
                            MIN(runs_conceded) AS best_bowling_figures
                        FROM match_performance
                        WHERE player_id = $player_id $formatCondition";

                $result = $conn->query($sql);

                // Check if the query failed
                if (!$result) {
                    die("Error in SQL query: " . $conn->error);
                }

                $row = $result->fetch_assoc();

                $bowling_metrics = [
                    'total_wickets' => $row['total_wickets'],
                    'total_overs_bowled' => $row['total_overs_bowled'],
                    'total_runs_conceded' => $row['total_runs_conceded'],
                    'economy_rate' => ($row['total_overs_bowled'] > 0) ? ($row['total_runs_conceded'] / $row['total_overs_bowled']) : 0,
                    'best_bowling_figures' => $row['best_bowling_figures'],
                    'bowling_average' => ($row['total_wickets'] > 0) ? $row['total_runs_conceded'] / $row['total_wickets'] : 0
                ];

                return $bowling_metrics;
            }

            // Function to calculate fielding metrics
            function calculateFieldingMetrics($player_id, $conn, $format) {
                $match_type = ($format == "All") ? "" : "$format over";
                $formatCondition = ($format == "All") ? "" : " AND match_type = '$match_type'";
                $sql = "SELECT 
                            SUM(catches) AS total_catches,
                            SUM(stumpings) AS total_stumpings,
                            SUM(run_outs) AS total_run_outs
                        FROM match_performance
                        WHERE player_id = $player_id $formatCondition";

                $result = $conn->query($sql);

                // Check if the query failed
                if (!$result) {
                    die("Error in SQL query: " . $conn->error);
                }

                $row = $result->fetch_assoc();

                $fielding_metrics = [
                    'total_catches' => $row['total_catches'],
                    'total_stumpings' => $row['total_stumpings'],
                    'total_run_outs' => $row['total_run_outs']
                ];

                return $fielding_metrics;
            }

            // Function to calculate total matches played
            function calculateTotalMatches($player_id, $conn, $format) {
                $match_type = ($format == "All") ? "" : "$format over";
                $formatCondition = ($format == "All") ? "" : " AND match_type = '$match_type'";
                $sql = "SELECT COUNT(*) AS total_matches FROM match_performance WHERE player_id = $player_id $formatCondition";
                $result = $conn->query($sql);

                // Check if the query failed
                if (!$result) {
                    die("Error in SQL query: " . $conn->error);
                }

                $row = $result->fetch_assoc();
                return $row['total_matches'];
            }

            // Calculate metrics for the selected player
            $batting_metrics = calculateBattingMetrics($player_id, $conn, $format);
            $bowling_metrics = calculateBowlingMetrics($player_id, $conn, $format);
            $fielding_metrics = calculateFieldingMetrics($player_id, $conn, $format);
            $total_matches = calculateTotalMatches($player_id, $conn, $format); // Updated to include format

            // Display the metrics in a table
            echo "<h2>Player Performance Metrics</h2>";
            echo "<table>";
            echo "<tr><th>Metric</th><th>Value</th></tr>";
            echo "<tr><td>Total Matches Played</td><td>" . $total_matches . "</td></tr>";
            echo "<tr><td>Total Runs</td><td>" . $batting_metrics['total_runs'] . "</td></tr>";
            echo "<tr><td>Batting Average</td><td>" . number_format($batting_metrics['batting_average'], 2) . "</td></tr>";
            echo "<tr><td>Strike Rate</td><td>" . number_format($batting_metrics['strike_rate'], 2) . "</td></tr>";
            echo "<tr><td>Total Fours</td><td>" . $batting_metrics['total_fours'] . "</td></tr>";
            echo "<tr><td>Total Sixes</td><td>" . $batting_metrics['total_sixes'] . "</td></tr>";
            echo "<tr><td>Half Centuries</td><td>" . $batting_metrics['half_centuries'] . "</td></tr>";
            echo "<tr><td>Centuries</td><td>" . $batting_metrics['centuries'] . "</td></tr>";
            echo "<tr><td>Total Wickets</td><td>" . $bowling_metrics['total_wickets'] . "</td></tr>";
            echo "<tr><td>Bowling Average</td><td>" . number_format($bowling_metrics['bowling_average'], 2) . "</td></tr>";
            echo "<tr><td>Economy Rate</td><td>" . number_format($bowling_metrics['economy_rate'], 2) . "</td></tr>";
            echo "<tr><td>Best Bowling Figures</td><td>" . $bowling_metrics['best_bowling_figures'] . "</td></tr>";
            echo "<tr><td>Total Catches</td><td>" . $fielding_metrics['total_catches'] . "</td></tr>";
            echo "<tr><td>Total Stumpings</td><td>" . $fielding_metrics['total_stumpings'] . "</td></tr>";
            echo "<tr><td>Total Run Outs</td><td>" . $fielding_metrics['total_run_outs'] . "</td></tr>";
            echo "</table>";

            // Export buttons
            echo "<div class='export-buttons'>";
            echo "<button onclick=\"exportToCSV()\">Export to CSV</button>";
            echo "<button onclick=\"exportToPDF()\">Export to PDF</button>";
            echo "</div>";

            $conn->close();
        }
        ?>

        <script>
            // Function to toggle dropdown menus
            function toggleMenu(menuId) {
                const menu = document.getElementById(menuId);
                if (menu.style.display === "block") {
                    menu.style.display = "none";
                } else {
                    menu.style.display = "block";
                }
            }

            // Function to export to CSV
            function exportToCSV() {
                const metrics = [
                    ["Metric", "Value"],
                    ["Total Matches Played", "<?php echo $total_matches; ?>"],
                    ["Total Runs", "<?php echo $batting_metrics['total_runs']; ?>"],
                    ["Batting Average", "<?php echo number_format($batting_metrics['batting_average'], 2); ?>"],
                    ["Strike Rate", "<?php echo number_format($batting_metrics['strike_rate'], 2); ?>"],
                    ["Total Fours", "<?php echo $batting_metrics['total_fours']; ?>"],
                    ["Total Sixes", "<?php echo $batting_metrics['total_sixes']; ?>"],
                    ["Half Centuries", "<?php echo $batting_metrics['half_centuries']; ?>"],
                    ["Centuries", "<?php echo $batting_metrics['centuries']; ?>"],
                    ["Total Wickets", "<?php echo $bowling_metrics['total_wickets']; ?>"],
                    ["Bowling Average", "<?php echo number_format($bowling_metrics['bowling_average'], 2); ?>"],
                    ["Economy Rate", "<?php echo number_format($bowling_metrics['economy_rate'], 2); ?>"],
                    ["Best Bowling Figures", "<?php echo $bowling_metrics['best_bowling_figures']; ?>"],
                    ["Total Catches", "<?php echo $fielding_metrics['total_catches']; ?>"],
                    ["Total Stumpings", "<?php echo $fielding_metrics['total_stumpings']; ?>"],
                    ["Total Run Outs", "<?php echo $fielding_metrics['total_run_outs']; ?>"]
                ];

                const csvContent = "data:text/csv;charset=utf-8," + metrics.map(e => e.join(",")).join("\n");
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "player_metrics.csv");
                document.body.appendChild(link);
                link.click();
            }

            // Function to export to PDF
            function exportToPDF() {
                window.print();
            }
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>