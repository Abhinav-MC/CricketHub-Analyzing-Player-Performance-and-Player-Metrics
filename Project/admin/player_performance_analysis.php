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

        canvas {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: black;
        }

        /* Add this CSS to control the size of the charts */
        .chart-size {
            height: 200px;
            width: 400px;
            background-color:white;
        }

        /* Add this CSS to style the buttons */
        button {
            background-color: #2DE1FC;
            color: #FFFFFF;
            border: none;
            padding: 10px 20px;
            margin: 10px 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #1B2735;
        }

        /* Add this CSS to reduce the size of the headings */
        h2 {
            font-size: 1.5rem;
            color: #2DE1FC;
            margin-top: 20px;
        }
        /* Style for the "Select Player" container */
.player-select-container {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Style for the label */
.player-select-container label {
    font-size: 1.2rem;
    color: #2DE1FC;
    font-weight: 500;
}

/* Style for the dropdown */
.player-select-container select {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #2DE1FC;
    background-color: #1B2735;
    color: #FFFFFF;
    font-size: 1rem;
    cursor: pointer;
    transition: border-color 0.3s ease;
    width: 200px; /* Adjust width as needed */
}

.player-select-container select:hover {
    border-color: #FFFFFF;
}

.player-select-container select option {
    background-color: #1B2735;
    color: #FFFFFF;
    padding: 10px;
}

.player-select-container select option:hover {
    background-color: #2DE1FC;
    color: #1B2735;
}

/* Style for the button */
.player-select-container button {
    background-color: #2DE1FC;
    color: #1B2735;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s ease;
}

.player-select-container button:hover {
    background-color: #1B2735;
    color: #2DE1FC;
}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
        <h1>Player Performance Analysis</h1>
        <form action="" method="GET">
            <div class="player-select-container">
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
                            $selected = (isset($_GET['player_id']) && $_GET['player_id'] == $row['id']) ? 'selected' : '';
                            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No players found</option>";
                    }

                    $conn->close();
                    ?>
                </select>
                <button type="submit">Analyze Performance</button>
            </div>
        </form>

        <?php
        // Check if the form is submitted
        if (isset($_GET['player_id'])) {
            $player_id = $_GET['player_id'];

            // Database connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Function to fetch match performance data for a specific format
            function fetchMatchPerformanceData($player_id, $conn, $match_type) {
                $sql = "SELECT match_date, runs_scored, wickets_taken, fours, sixes FROM match_performance WHERE player_id = $player_id AND match_type = '$match_type' ORDER BY match_date";
                $result = $conn->query($sql);

                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                return $data;
            }

            // Function to fetch peer comparison data for a specific format
            function fetchPeerComparisonData($player_id, $conn, $match_type) {
                $sql = "SELECT p.name, SUM(mp.runs_scored) AS total_runs, SUM(mp.wickets_taken) AS total_wickets
                        FROM match_performance mp
                        JOIN players p ON mp.player_id = p.id
                        WHERE mp.player_id != $player_id AND mp.match_type = '$match_type'
                        GROUP BY mp.player_id";
                $result = $conn->query($sql);

                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                return $data;
            }

            // Function to fetch performance in last 5 matches for a specific format
            function fetchLast5MatchesData($player_id, $conn, $match_type) {
                $sql = "SELECT match_date, runs_scored, wickets_taken FROM match_performance WHERE player_id = $player_id AND match_type = '$match_type' ORDER BY match_date DESC LIMIT 5";
                $result = $conn->query($sql);

                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                return $data;
            }

            // Fetch match performance data for 20 Over, 50 Over, and 90 Over
            $match_performance_data_20_over = fetchMatchPerformanceData($player_id, $conn, '20 Over');
            $match_performance_data_50_over = fetchMatchPerformanceData($player_id, $conn, '50 Over');
            $match_performance_data_90_over = fetchMatchPerformanceData($player_id, $conn, '90 Over');

            // Fetch peer comparison data for 20 Over, 50 Over, and 90 Over
            $peer_comparison_data_20_over = fetchPeerComparisonData($player_id, $conn, '20 Over');
            $peer_comparison_data_50_over = fetchPeerComparisonData($player_id, $conn, '50 Over');
            $peer_comparison_data_90_over = fetchPeerComparisonData($player_id, $conn, '90 Over');

            // Fetch performance in last 5 matches for 20 Over, 50 Over, and 90 Over
            $last_5_matches_data_20_over = fetchLast5MatchesData($player_id, $conn, '20 Over');
            $last_5_matches_data_50_over = fetchLast5MatchesData($player_id, $conn, '50 Over');
            $last_5_matches_data_90_over = fetchLast5MatchesData($player_id, $conn, '90 Over');

            $conn->close();
        }
        ?>

        <?php if (isset($match_performance_data_20_over) && !empty($match_performance_data_20_over)): ?>
            <h2>20 Over Performance Trend Analysis</h2>
            <canvas id="performanceTrendChart20Over" class="chart-size"></canvas>
            <button onclick="exportChart('performanceTrendChart20Over', 'performance_trend_20_over.png')">Export as Image</button>
            <button onclick="exportChartToPDF('performanceTrendChart20Over', 'performance_trend_20_over.pdf')">Export as PDF</button>
            <script>
                const matchDates20Over = <?php echo json_encode(array_column($match_performance_data_20_over, 'match_date')); ?>;
                const runsScored20Over = <?php echo json_encode(array_column($match_performance_data_20_over, 'runs_scored')); ?>;
                const wicketsTaken20Over = <?php echo json_encode(array_column($match_performance_data_20_over, 'wickets_taken')); ?>;

                const ctx20Over = document.getElementById('performanceTrendChart20Over').getContext('2d');
                new Chart(ctx20Over, {
                    type: 'line',
                    data: {
                        labels: matchDates20Over,
                        datasets: [
                            {
                                label: 'Runs Scored (20 Over)',
                                data: runsScored20Over,
                                borderColor: 'black',
                                fill: false
                            },
                            {
                                label: 'Wickets Taken (20 Over)',
                                data: wicketsTaken20Over,
                                borderColor: 'red',
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Match Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Value'
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <?php if (isset($peer_comparison_data_20_over) && !empty($peer_comparison_data_20_over)): ?>
            <h2>20 Over Peer Comparison (Bar Chart)</h2>
            <canvas id="peerComparisonChart20Over" class="chart-size"></canvas>
            <button onclick="exportChart('peerComparisonChart20Over', 'peer_comparison_20_over.png')">Export as Image</button>
            <button onclick="exportChartToPDF('peerComparisonChart20Over', 'peer_comparison_20_over.pdf')">Export as PDF</button>
            <script>
                const playerNames20Over = <?php echo json_encode(array_column($peer_comparison_data_20_over, 'name')); ?>;
                const totalRuns20Over = <?php echo json_encode(array_column($peer_comparison_data_20_over, 'total_runs')); ?>;
                const totalWickets20Over = <?php echo json_encode(array_column($peer_comparison_data_20_over, 'total_wickets')); ?>;

                const ctxBar20Over = document.getElementById('peerComparisonChart20Over').getContext('2d');
                new Chart(ctxBar20Over, {
                    type: 'bar',
                    data: {
                        labels: playerNames20Over,
                        datasets: [
                            {
                                label: 'Total Runs (20 Over)',
                                data: totalRuns20Over,
                                backgroundColor: 'blue',
                                color:'white'
                            },
                            {
                                label: 'Total Wickets (20 Over)',
                                data: totalWickets20Over,
                                backgroundColor: 'red',
                                color:'white'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Player Name',
                                    color:'white'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Value',
                                    color:'white'
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <?php if (isset($match_performance_data_50_over) && !empty($match_performance_data_50_over)): ?>
            <h2>50 Over Performance Trend Analysis</h2>
            <canvas id="performanceTrendChart50Over" class="chart-size"></canvas>
            <button onclick="exportChart('performanceTrendChart50Over', 'performance_trend_50_over.png')">Export as Image</button>
            <button onclick="exportChartToPDF('performanceTrendChart50Over', 'performance_trend_50_over.pdf')">Export as PDF</button>
            <script>
                const matchDates50Over = <?php echo json_encode(array_column($match_performance_data_50_over, 'match_date')); ?>;
                const runsScored50Over = <?php echo json_encode(array_column($match_performance_data_50_over, 'runs_scored')); ?>;
                const wicketsTaken50Over = <?php echo json_encode(array_column($match_performance_data_50_over, 'wickets_taken')); ?>;

                const ctx50Over = document.getElementById('performanceTrendChart50Over').getContext('2d');
                new Chart(ctx50Over, {
                    type: 'line',
                    data: {
                        labels: matchDates50Over,
                        datasets: [
                            {
                                label: 'Runs Scored (50 Over)',
                                data: runsScored50Over,
                                borderColor: 'green',
                                fill: false
                            },
                            {
                                label: 'Wickets Taken (50 Over)',
                                data: wicketsTaken50Over,
                                borderColor: 'orange',
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Match Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Value'
                                    
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <?php if (isset($peer_comparison_data_50_over) && !empty($peer_comparison_data_50_over)): ?>
            <h2>50 Over Peer Comparison (Bar Chart)</h2>
            <canvas id="peerComparisonChart50Over" class="chart-size"></canvas>
            <button onclick="exportChart('peerComparisonChart50Over', 'peer_comparison_50_over.png')">Export as Image</button>
            <button onclick="exportChartToPDF('peerComparisonChart50Over', 'peer_comparison_50_over.pdf')">Export as PDF</button>
            <script>
                const playerNames50Over = <?php echo json_encode(array_column($peer_comparison_data_50_over, 'name')); ?>;
                const totalRuns50Over = <?php echo json_encode(array_column($peer_comparison_data_50_over, 'total_runs')); ?>;
                const totalWickets50Over = <?php echo json_encode(array_column($peer_comparison_data_50_over, 'total_wickets')); ?>;

                const ctxBar50Over = document.getElementById('peerComparisonChart50Over').getContext('2d');
                new Chart(ctxBar50Over, {
                    type: 'bar',
                    data: {
                        labels: playerNames50Over,
                        datasets: [
                            {
                                label: 'Total Runs (50 Over)',
                                data: totalRuns50Over,
                                backgroundColor: 'green'
                            },
                            {
                                label: 'Total Wickets (50 Over)',
                                data: totalWickets50Over,
                                backgroundColor: 'orange'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Player Name'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Value'
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <?php if (isset($match_performance_data_90_over) && !empty($match_performance_data_90_over)): ?>
            <h2>90 Over Performance Trend Analysis</h2>
            <canvas id="performanceTrendChart90Over" class="chart-size"></canvas>
            <button onclick="exportChart('performanceTrendChart90Over', 'performance_trend_90_over.png')">Export as Image</button>
            <button onclick="exportChartToPDF('performanceTrendChart90Over', 'performance_trend_90_over.pdf')">Export as PDF</button>
            <script>
                const matchDates90Over = <?php echo json_encode(array_column($match_performance_data_90_over, 'match_date')); ?>;
                const runsScored90Over = <?php echo json_encode(array_column($match_performance_data_90_over, 'runs_scored')); ?>;
                const wicketsTaken90Over = <?php echo json_encode(array_column($match_performance_data_90_over, 'wickets_taken')); ?>;

                const ctx90Over = document.getElementById('performanceTrendChart90Over').getContext('2d');
                new Chart(ctx90Over, {
                    type: 'line',
                    data: {
                        labels: matchDates90Over,
                        datasets: [
                            {
                                label: 'Runs Scored (90 Over)',
                                data: runsScored90Over,
                                borderColor: 'purple',
                                fill: false
                            },
                            {
                                label: 'Wickets Taken (90 Over)',
                                data: wicketsTaken90Over,
                                borderColor: 'pink',
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Match Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Value'
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <?php if (isset($peer_comparison_data_90_over) && !empty($peer_comparison_data_90_over)): ?>
            <h2>90 Over Peer Comparison (Bar Chart)</h2>
            <canvas id="peerComparisonChart90Over" class="chart-size"></canvas>
            <button onclick="exportChart('peerComparisonChart90Over', 'peer_comparison_90_over.png')">Export as Image</button>
            <button onclick="exportChartToPDF('peerComparisonChart90Over', 'peer_comparison_90_over.pdf')">Export as PDF</button>
            <script>
                const playerNames90Over = <?php echo json_encode(array_column($peer_comparison_data_90_over, 'name')); ?>;
                const totalRuns90Over = <?php echo json_encode(array_column($peer_comparison_data_90_over, 'total_runs')); ?>;
                const totalWickets90Over = <?php echo json_encode(array_column($peer_comparison_data_90_over, 'total_wickets')); ?>;

                const ctxBar90Over = document.getElementById('peerComparisonChart90Over').getContext('2d');
                new Chart(ctxBar90Over, {
                    type: 'bar',
                    data: {
                        labels: playerNames90Over,
                        datasets: [
                            {
                                label: 'Total Runs (90 Over)',
                                data: totalRuns90Over,
                                backgroundColor: 'purple'
                            },
                            {
                                label: 'Total Wickets (90 Over)',
                                data: totalWickets90Over,
                                backgroundColor: 'pink'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Player Name'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Value'
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <?php if (isset($last_5_matches_data_20_over) && !empty($last_5_matches_data_20_over)): ?>
            <h2>Performance in Last 5 20 Over Matches</h2>
            <table>
                <tr>
                    <th>Match Date</th>
                    <th>Runs Scored</th>
                    <th>Wickets Taken</th>
                </tr>
                <?php foreach ($last_5_matches_data_20_over as $match): ?>
                    <tr>
                        <td><?php echo $match['match_date']; ?></td>
                        <td><?php echo $match['runs_scored']; ?></td>
                        <td><?php echo $match['wickets_taken']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if (isset($last_5_matches_data_50_over) && !empty($last_5_matches_data_50_over)): ?>
            <h2>Performance in Last 5 50 Over Matches</h2>
            <table>
                <tr>
                    <th>Match Date</th>
                    <th>Runs Scored</th>
                    <th>Wickets Taken</th>
                </tr>
                <?php foreach ($last_5_matches_data_50_over as $match): ?>
                    <tr>
                        <td><?php echo $match['match_date']; ?></td>
                        <td><?php echo $match['runs_scored']; ?></td>
                        <td><?php echo $match['wickets_taken']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if (isset($last_5_matches_data_90_over) && !empty($last_5_matches_data_90_over)): ?>
            <h2>Performance in Last 5 90 Over Matches</h2>
            <table>
                <tr>
                    <th>Match Date</th>
                    <th>Runs Scored</th>
                    <th>Wickets Taken</th>
                </tr>
                <?php foreach ($last_5_matches_data_90_over as $match): ?>
                    <tr>
                        <td><?php echo $match['match_date']; ?></td>
                        <td><?php echo $match['runs_scored']; ?></td>
                        <td><?php echo $match['wickets_taken']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <script>
        // Function to export chart as image
        function exportChart(chartId, fileName) {
            const chartCanvas = document.getElementById(chartId);
            html2canvas(chartCanvas).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.href = imgData;
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }

        // Function to export chart as PDF
        function exportChartToPDF(chartId, fileName) {
            const chartCanvas = document.getElementById(chartId);
            html2canvas(chartCanvas).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jspdf.jsPDF();
                const imgWidth = 210;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                pdf.save(fileName);
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>