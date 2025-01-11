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

// Function to calculate monthly report
function getMonthlyReport($conn, $month, $year) {
    // Fetch match details for the selected month and year
    $sql = "SELECT * FROM match_details 
            WHERE MONTH(match_date) = ? 
            AND YEAR(match_date) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $match_result = $stmt->get_result();

    // Fetch player performance for the selected month and year
    $sql_performance = "SELECT mp.*, p.name AS player_name 
                        FROM match_performance mp
                        JOIN players p ON mp.player_id = p.id
                        WHERE MONTH(mp.match_date) = ? 
                        AND YEAR(mp.match_date) = ?";
    $stmt_performance = $conn->prepare($sql_performance);
    $stmt_performance->bind_param("ii", $month, $year);
    $stmt_performance->execute();
    $performance_result = $stmt_performance->get_result();

    // Fetch total runs and wickets for the selected month and year
    $sql_totals = "SELECT 
                    SUM(mp.runs_scored) AS total_runs,
                    SUM(mp.wickets_taken) AS total_wickets
                   FROM match_performance mp
                   WHERE MONTH(mp.match_date) = ? 
                   AND YEAR(mp.match_date) = ?";
    $stmt_totals = $conn->prepare($sql_totals);
    $stmt_totals->bind_param("ii", $month, $year);
    $stmt_totals->execute();
    $totals_result = $stmt_totals->get_result();
    $totals = $totals_result->fetch_assoc();

    return [
        'match_details' => $match_result,
        'player_performance' => $performance_result,
        'totals' => $totals
    ];
}

// Check if the form is submitted
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $_GET['month'];
    $year = $_GET['year'];

    $report = getMonthlyReport($conn, $month, $year);
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
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

        .card {
            display: inline-block;
            width: 30%;
            margin: 10px;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .card p {
            margin: 10px 0 0;
            font-size: 24px;
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: black;
        }

        th {
            background-color: white;
        }

        .chart-container {
            width: 100%;
            margin-top: 20px;
        }

        .pie-chart-container {
            width: 300px; /* Adjust the width of the pie chart */
            height: 300px; /* Adjust the height of the pie chart */
            margin: 0 auto; /* Center the pie chart */
        }

        .export-buttons {
            margin-top: 20px;
        }

        .export-buttons button {
            margin-right: 10px;
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
        <div class="container">
            <h1>Monthly Report </h1>
            <form action="weekly_report.php" method="GET">
                <label for="month">Select Month:</label>
                <select name="month" id="month" required>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>

                <label for="year">Select Year:</label>
                <select name="year" id="year" required>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>

                <button type="submit">Generate Report</button>
            </form>

            <?php if (isset($report)) { ?>
                <h2>Monthly Report for <?php echo date("F", mktime(0, 0, 0, $month, 1)) . " $year"; ?></h2>

                <!-- Key Metrics (Cards) -->
                <div class="card">
                    <h3>Total Matches</h3>
                    <p><?php echo $report['match_details']->num_rows; ?></p>
                </div>
                <div class="card">
                    <h3>Total Runs</h3>
                    <p><?php echo $report['totals']['total_runs']; ?></p>
                </div>
                <div class="card">
                    <h3>Total Wickets</h3>
                    <p><?php echo $report['totals']['total_wickets']; ?></p>
                </div>

                <!-- Display Match Details -->
                <h3>Match Details</h3>
                <table>
                    <tr>
                        <th>Match ID</th>
                        <th>Team 1</th>
                        <th>Team 2</th>
                        <th>Match Date</th>
                        <th>Match Type</th>
                        <th>Match Result</th>
                    </tr>
                    <?php while ($row = $report['match_details']->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['match_id']; ?></td>
                            <td><?php echo $row['team1']; ?></td>
                            <td><?php echo $row['team2']; ?></td>
                            <td><?php echo $row['match_date']; ?></td>
                            <td><?php echo $row['match_type']; ?></td>
                            <td><?php echo $row['match_result']; ?></td>
                        </tr>
                    <?php } ?>
                </table>

                <!-- Display Player Performance -->
                <h3>Player Performance</h3>
                <table>
                    <tr>
                        <th>Player Name</th>
                        <th>Runs Scored</th>
                        <th>Wickets Taken</th>
                        <th>Match Date</th>
                    </tr>
                    <?php while ($row = $report['player_performance']->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['player_name']; ?></td>
                            <td><?php echo $row['runs_scored']; ?></td>
                            <td><?php echo $row['wickets_taken']; ?></td>
                            <td><?php echo $row['match_date']; ?></td>
                        </tr>
                    <?php } ?>
                </table>

                <!-- Bar Chart for Runs and Wickets -->
                <h3>Runs and Wickets (Bar Chart)</h3>
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>

                <!-- Export Buttons -->
                <div class="export-buttons">
                    <button onclick="exportToCSV()">Export to CSV</button>
                    <button onclick="exportToPDF()">Export to PDF</button>
                </div>

                <script>
                    // Bar Chart for Runs and Wickets
                    var performanceChart = new Chart(document.getElementById('performanceChart'), {
                        type: 'bar',
                        data: {
                            labels: ['Runs', 'Wickets'],
                            datasets: [{
                                label: 'Performance',
                                data: [<?php echo $report['totals']['total_runs']; ?>, <?php echo $report['totals']['total_wickets']; ?>],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 99, 132, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)'
                                ],
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

                    // Pie Chart for Match Results
                </script>
            <?php } ?>
        </div>
    </div>

    <script>
        function exportToCSV() {
            alert("CSV export functionality will be implemented here.");
        }

        function exportToPDF() {
            window.print();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>