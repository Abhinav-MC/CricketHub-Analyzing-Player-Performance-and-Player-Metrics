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

// Function to fetch total players
function getTotalPlayers($conn) {
    $sql = "SELECT COUNT(*) AS total FROM players";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch total matches
function getTotalMatches($conn) {
    $sql = "SELECT COUNT(*) AS total FROM match_details";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch total trainees
function getTotalTrainees($conn) {
    $sql = "SELECT COUNT(*) AS total FROM trainees";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch total tournaments
function getTotalTournaments($conn) {
    $sql = "SELECT COUNT(*) AS total FROM tournaments";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch pending approvals
function getPendingApprovals($conn) {
    $sql = "SELECT COUNT(*) AS total FROM players WHERE approval_status = 'pending'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch rejected approvals
function getRejectedApprovals($conn) {
    $sql = "SELECT COUNT(*) AS total FROM players WHERE approval_status = 'rejected'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch accepted approvals
function getAcceptedApprovals($conn) {
    $sql = "SELECT COUNT(*) AS total FROM players WHERE approval_status = 'approved'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch total batsmen
function getTotalBatsmen($conn) {
    $sql = "SELECT COUNT(*) AS total FROM player_registration WHERE player_role = 'Batsman'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch total bowlers
function getTotalBowlers($conn) {
    $sql = "SELECT COUNT(*) AS total FROM player_registration WHERE player_role = 'Bowler'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch matches won
function getMatchesWon($conn) {
    $sql = "SELECT COUNT(*) AS total FROM match_details WHERE match_result = 'Won'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Function to fetch matches lost
function getMatchesLost($conn) {
    $sql = "SELECT COUNT(*) AS total FROM match_details WHERE match_result = 'lost'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}


// Function to fetch total enquiries
function getTotalEnquiries($conn) {
    $sql = "SELECT COUNT(*) AS total FROM enquiries";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

// Fetch all metrics
$total_players = getTotalPlayers($conn);
$total_matches = getTotalMatches($conn);
$total_trainees = getTotalTrainees($conn);
$total_tournaments = getTotalTournaments($conn);
$pending_approvals = getPendingApprovals($conn);
$accepted_approvals = getAcceptedApprovals($conn);
$total_batsmen = getTotalBatsmen($conn);
$total_bowlers = getTotalBowlers($conn);
$matches_won = getMatchesWon($conn);
$matches_lost = getMatchesLost($conn);
$total_enquiries = getTotalEnquiries($conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CricketHub </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

        .card.matches { background-color: #2DE1FC; }
        .card.players { background-color: #4CAF50; }
        .card.trainees { background-color: #FFA726; }
        .card.wins { background-color: #FFC107; }
        .card.losses { background-color: #FF5722; }
        .card.batsmen { background-color: #8E44AD; }
        .card.bowlers { background-color: #2980B9; }
        .card.allrounders { background-color: #16A085; }
        .card.pending-approvals { background-color: #FF5722; }
        .card.accepted-approvals { background-color: #28a745; }

        .charts-container {
            margin-top: 20px;
        }

        .chart-box {
            background: #1B2735;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
        }

        .chart-box h5 {
            color: #2DE1FC;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
        }

        .apexcharts-menu {
        background-color: : black; /* Background color of the menu */
        position: absolute;
        top: 100%;
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 3px;
        right: 10px;
        opacity: 0;
        min-width: 110px;
        transition: .15s ease all;
        pointer-events: none;
        color: black; /* Set the text color to white for better visibility */
}



    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
    <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-trophy"></i> Players</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="Player_register.php"><i class="fas fa-plus"></i> Add Player</a>
            <a class="dropdown-item" href="total_players.php"><i class="fas fa-list"></i> View Players</a>
            <a class="dropdown-item" href="edit_players.php"><i class="fas fa-list"></i> Manage Players</a>
            <a class="dropdown-item" href="manage_players.php"><i class="fas fa-list"></i> Manage Request</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-trophy"></i> Player Performance</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="player_performance_metrics.php"><i class="fas fa-plus"></i> Player Performance Metrics</a>
            <a class="dropdown-item" href="player_performance_analysis.php"><i class="fas fa-list"></i> Player Performance Analysis</a>
            <a class="dropdown-item" href="match_performance.php"><i class="fas fa-list"></i> Match Performance</a>
            <a class="dropdown-item" href="match_details.php"><i class="fas fa-list"></i> Match Details</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-trophy"></i> Trainee</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="trainee_registration.php"><i class="fas fa-plus"></i> Add Trainee</a>
            <a class="dropdown-item" href="edit_trainee.php"><i class="fas fa-list"></i> Manage Trainee</a>
            <a class="dropdown-item" href="display_trainees.php"><i class="fas fa-list"></i> View Trainee</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-trophy"></i> Tournaments</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="add_tournament.php"><i class="fas fa-plus"></i> Add Tournament</a>
            <a class="dropdown-item" href="view_tournaments.php"><i class="fas fa-list"></i> View Tournaments</a>
        </div>
    </div>
    <a href="weekly_report.php"><i class="fas fa-file-alt"></i> Report</a>
    <a href="enquiries.php"><i class="fas fa-user-plus"></i> Enquiries</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        

        <!-- Cards Section -->
        <div class="row">
            <div class="col-md-3">
                <div class="card matches">
                    <div class="icon">🏏</div>
                    <h5>Total Matches</h5>
                    <h2><?php echo $total_matches; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card players">
                    <div class="icon">👥</div>
                    <h5>Total Players</h5>
                    <h2><?php echo $total_players; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card batsmen">
                    <div class="icon">🏌️</div>
                    <h5>Total Batsmen</h5>
                    <h2><?php echo $total_batsmen; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bowlers">
                    <div class="icon">🏏</div>
                    <h5>Total Bowlers</h5>
                    <h2><?php echo $total_bowlers; ?></h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card allrounders">
                    <div class="icon">💪</div>
                    <h5>Total All-rounders</h5>
                    <h2><?php echo $total_players - ($total_batsmen + $total_bowlers); ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card wins">
                    <div class="icon">🏆</div>
                    <h5>Matches Won</h5>
                    <h2><?php echo $matches_won; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card losses">
                    <div class="icon">❌</div>
                    <h5>Matches Lost</h5>
                    <h2><?php echo $matches_lost; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card pending-approvals">
                    <div class="icon">⏳</div>
                    <h5>Pending Approvals</h5>
                    <h2><?php echo $pending_approvals; ?></h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card accepted-approvals">
                    <div class="icon">✅</div>
                    <h5>Accepted Approvals</h5>
                    <h2><?php echo $accepted_approvals; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card wins">
                    <div class="icon">✅</div>
                    <h5>Enquires</h5>
                    <h2><?php echo $total_enquiries; ?></h2>
                </div>
            </div>
        </div>



        <!-- Charts Section -->
        <div class="charts-container">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-box">
                        <h5>Player Type Distribution</h5>
                        <div id="pieChart"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-box">
                        <h5>Win vs Loss Ratio</h5>
                        <div id="barChart"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer>
        © 2025 CricketHub 
    </footer>

    <!-- ApexCharts -->
    <script>
        // Pie Chart for Player Distribution
        var optionsPie = {
            chart: {
                type: 'pie',
                height: 350,
                foreColor: '#FFFFFF'
            },
            series: [<?php echo $total_batsmen; ?>, <?php echo $total_bowlers; ?>, <?php echo $total_players - ($total_batsmen + $total_bowlers); ?>],
            labels: ['Batsmen', 'Bowlers', 'All-rounders'],
            colors: ['#8E44AD', '#2980B9', '#16A085']
        };

        var chartPie = new ApexCharts(document.querySelector("#pieChart"), optionsPie);
        chartPie.render();

        // Bar Chart for Wins and Losses
        var optionsBar = {
            chart: {
                type: 'bar',
                height: 350,
                foreColor: '#FFFFFF'
            },
            series: [{
                name: 'Matches',
                data: [<?php echo $matches_won; ?>, <?php echo $matches_lost; ?>]
            }],
            xaxis: {
                categories: ['Wins', 'Losses']
            },
            colors: ['#2DE1FC', '#FF5722']
        };

        var chartBar = new ApexCharts(document.querySelector("#barChart"), optionsBar);
        chartBar.render();
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>