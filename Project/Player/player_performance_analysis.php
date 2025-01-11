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

// Fetch performance in last 5 matches for 20 Over, 50 Over, and 90 Over
$last_5_matches_data_20_over = fetchLast5MatchesData($player_id, $conn, '20 Over');
$last_5_matches_data_50_over = fetchLast5MatchesData($player_id, $conn, '50 Over');
$last_5_matches_data_90_over = fetchLast5MatchesData($player_id, $conn, '90 Over');

function calculateStrikeRate($player_id, $conn, $match_type) {
    $sql = "SELECT SUM(runs_scored) AS total_runs, SUM(balls_faced) AS total_balls_faced 
            FROM match_performance 
            WHERE player_id = $player_id AND match_type = '$match_type'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_runs = $row['total_runs'];
        $total_balls_faced = $row['total_balls_faced'];

        if ($total_balls_faced > 0) {
            return ($total_runs / $total_balls_faced) * 100;
        }
    }

    return 0;
}

// Calculate strike rates for each format
$strike_rate_20_over = calculateStrikeRate($player_id, $conn, '20 Over');
$strike_rate_50_over = calculateStrikeRate($player_id, $conn, '50 Over');
$strike_rate_90_over = calculateStrikeRate($player_id, $conn, '90 Over');


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Performance Analysis</title>
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
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        button {
            padding: 10px 20px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #218838;
        }
    </style>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
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
                    <i class="fas fa-user"></i> Report
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
            <h1>Performance Analysis</h1>
            <div class="user-info">Player ID: <?php echo $player_id; ?></div>
        </div>

        <!-- 20 Over Performance Trend Analysis -->
        <?php if (isset($match_performance_data_20_over) && !empty($match_performance_data_20_over)): ?>
            <h2>20 Over Performance Trend Analysis</h2>
            <canvas id="performanceTrendChart20Over"></canvas>
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
                                borderColor: 'blue',
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

        <!-- 50 Over Performance Trend Analysis -->
        <?php if (isset($match_performance_data_50_over) && !empty($match_performance_data_50_over)): ?>
            <h2>50 Over Performance Trend Analysis</h2>
            <canvas id="performanceTrendChart50Over"></canvas>
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

        <!-- 90 Over Performance Trend Analysis -->
        <?php if (isset($match_performance_data_90_over) && !empty($match_performance_data_90_over)): ?>
            <h2>90 Over Performance Trend Analysis</h2>
            <canvas id="performanceTrendChart90Over"></canvas>
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

        <!-- Runs in Last 5 Matches (Column Chart) -->
        <h2>Runs in Last 5 Matches (20 Over, 50 Over, 90 Over)</h2>
        <canvas id="runsChart"></canvas>
        <button onclick="exportChart('runsChart', 'runs_last_5_matches.png')">Export as Image</button>
        <button onclick="exportChartToPDF('runsChart', 'runs_last_5_matches.pdf')">Export as PDF</button>

        <!-- Wickets in Last 5 Matches (Column Chart) -->
        <h2>Wickets in Last 5 Matches (20 Over, 50 Over, 90 Over)</h2>
        <canvas id="wicketsChart"></canvas>
        <button onclick="exportChart('wicketsChart', 'wickets_last_5_matches.png')">Export as Image</button>
        <button onclick="exportChartToPDF('wicketsChart', 'wickets_last_5_matches.pdf')">Export as PDF</button>

        <h2>Overall Strike Rates</h2>
        <canvas id="strikeRateChart"></canvas>
        <button onclick="exportChart('strikeRateChart', 'strike_rate_chart.png')">Export as Image</button>
        <button onclick="exportChartToPDF('strikeRateChart', 'strike_rate_chart.pdf')">Export as PDF</button>
    </div>

    <script>
        // Runs in Last 5 Matches Data
        const runsData = {
            labels: ['20 Over', '50 Over', '90 Over'],
            datasets: [
                {
                    label: 'Runs Scored',
                    data: [
                        <?php echo array_sum(array_column($last_5_matches_data_20_over, 'runs_scored')); ?>,
                        <?php echo array_sum(array_column($last_5_matches_data_50_over, 'runs_scored')); ?>,
                        <?php echo array_sum(array_column($last_5_matches_data_90_over, 'runs_scored')); ?>
                    ],
                    backgroundColor: ['blue', 'green', 'purple'],
                    borderColor: ['blue', 'green', 'purple'],
                    borderWidth: 1
                }
            ]
        };

        // Wickets in Last 5 Matches Data
        const wicketsData = {
            labels: ['20 Over', '50 Over', '90 Over'],
            datasets: [
                {
                    label: 'Wickets Taken',
                    data: [
                        <?php echo array_sum(array_column($last_5_matches_data_20_over, 'wickets_taken')); ?>,
                        <?php echo array_sum(array_column($last_5_matches_data_50_over, 'wickets_taken')); ?>,
                        <?php echo array_sum(array_column($last_5_matches_data_90_over, 'wickets_taken')); ?>
                    ],
                    backgroundColor: ['red', 'orange', 'pink'],
                    borderColor: ['red', 'orange', 'pink'],
                    borderWidth: 1
                }
            ]
        };

        // Runs Chart
        const runsChart = new Chart(document.getElementById('runsChart').getContext('2d'), {
            type: 'bar',
            data: runsData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Runs in Last 5 Matches'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Match Type'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Runs Scored'
                        }
                    }
                }
            }
        });

        // Wickets Chart
        const wicketsChart = new Chart(document.getElementById('wicketsChart').getContext('2d'), {
            type: 'bar',
            data: wicketsData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Wickets in Last 5 Matches'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Match Type'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Wickets Taken'
                        }
                    }
                }
            }
        });

        const strikeRateData = {
            labels: ['20 Over (T20)', '50 Over (ODI)', '90 Over (Test)'],
            datasets: [{
                label: 'Strike Rate',
                data: [
                    <?php echo $strike_rate_20_over; ?>,
                    <?php echo $strike_rate_50_over; ?>,
                    <?php echo $strike_rate_90_over; ?>
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)', // Red for T20
                    'rgba(54, 162, 235, 0.6)', // Blue for ODI
                    'rgba(75, 192, 192, 0.6)'  // Green for Test
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Chart configuration
        const strikeRateConfig = {
            type: 'bar', // Change to 'pie' for a pie chart
            data: strikeRateData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Overall Strike Rates by Format'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Strike Rate'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Match Format'
                        }
                    }
                }
            }
        };

        // Render the chart
        const strikeRateChart = new Chart(
            document.getElementById('strikeRateChart'),
            strikeRateConfig
        );

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
</body>
</html>