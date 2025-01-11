<?php
// admin/enquiries.php

// Database connection (replace with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cricket";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission (if POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert data into the database
    $sql = "INSERT INTO enquiries (full_name, email, message) VALUES ('$full_name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Message saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all enquiries from the database
$sql = "SELECT * FROM enquiries ORDER BY id DESC";
$result = $conn->query($sql); // Execute the query and store the result

if (!$result) {
    die("Query failed: " . $conn->error); // Handle query failure
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
            margin-bottom: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            display: block; /* Ensure the message is visible */
        }

        /* Ensure the table is visible */
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow-x: auto; /* Add horizontal scroll if table is too wide */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            color:black;
        }

        table th {
            background-color: #f2f2f2;
            color: black;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

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
        <!-- Enquiries Section -->
        <section class="py-12">
            <div class="container mx-auto px-6">
                <h1 class="text-4xl font-bold text-center mb-8">Admin Enquiries</h1>

                <!-- Display Enquiries in a Table -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . $row["id"] . "</td>
                                            <td>" . $row["full_name"] . "</td>
                                            <td>" . $row["email"] . "</td>
                                            <td>" . $row["message"] . "</td>
                                            <td>" . $row["created_at"] . "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No enquiries found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>