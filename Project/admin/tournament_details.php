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

// Initialize variables
$tournament_id = null;
$name = $start_date = $end_date = $location = $description = $photo_url = "";
$error_message = $success_message = "";

// Handle Add/Edit Tournament
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle photo upload (optional)
    $photo_url = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is valid
        if (getimagesize($_FILES["photo"]["tmp_name"]) !== false) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo_url = $target_file;
            }
        }
    }

    // Check if editing or adding
    if (isset($_POST['tournament_id']) && !empty($_POST['tournament_id'])) {
        // Editing existing tournament
        $tournament_id = $_POST['tournament_id'];
        $sql = "UPDATE tournaments SET
                name = '$name',
                start_date = '$start_date',
                end_date = '$end_date',
                location = '$location',
                description = '$description',
                photo_url = '$photo_url'
                WHERE id = $tournament_id";
    } else {
        // Adding new tournament
        $sql = "INSERT INTO tournaments (name, start_date, end_date, location, description, photo_url)
                VALUES ('$name', '$start_date', '$end_date', '$location', '$description', '$photo_url')";
    }

    if ($conn->query($sql) === TRUE) {
        $success_message = "Tournament saved successfully!";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch tournament details for editing
if (isset($_GET['edit'])) {
    $tournament_id = $_GET['edit'];
    $sql = "SELECT * FROM tournaments WHERE id = $tournament_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        $location = $row['location'];
        $description = $row['description'];
        $photo_url = $row['photo_url'];
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

        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 1em;
        }

        .form-group button {
            background-color: red;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 20px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #2c3e50;
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
        <h1>Tournament Form</h1>

        <!-- Success/Error Messages -->
        <?php if (!empty($success_message)) { ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if (!empty($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Tournament Form -->
        <form action="tournament_details.php" method="POST" enctype="multipart/form-data" class="form-container">
            <input type="hidden" name="tournament_id" value="<?php echo $tournament_id; ?>">

            <div class="form-group">
                <label for="name">Tournament Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo $location; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?php echo $description; ?></textarea>
            </div>

            <div class="form-group">
                <label for="photo">Tournament Photo:</label>
                <input type="file" id="photo" name="photo">
                <?php if ($photo_url) { ?>
                    <img src="<?php echo $photo_url; ?>" alt="Tournament Photo" style="max-width: 200px; margin-top: 10px;">
                <?php } ?>
            </div>

            <div class="form-group">
                <button type="submit"><?php echo $tournament_id ? "Update Tournament" : "Add Tournament"; ?></button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>