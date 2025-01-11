<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cricket"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$id = $name = $age = $gender = $email = $phone_number = $role = $batting_style = $bowling_style = $total_matches_played = $total_runs = $total_wickets = $experience_level = $achievements = $photo = "";
$success_message = $error_message = "";

// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the trainee's data from the database
    $sql = "SELECT * FROM trainees WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $age = $row['age'];
        $gender = $row['gender'];
        $email = $row['email'];
        $phone_number = $row['phone_number'];
        $role = $row['role'];
        $batting_style = $row['batting_style'];
        $bowling_style = $row['bowling_style'];
        $total_matches_played = $row['total_matches_played'];
        $total_runs = $row['total_runs'];
        $total_wickets = $row['total_wickets'];
        $experience_level = $row['experience_level'];
        $achievements = $row['achievements'];
        $photo = $row['photo'];
    } else {
        $error_message = "Trainee not found.";
    }
} else {
    $error_message = "Trainee ID not provided.";
}

// Handle form submission for updating the trainee
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];
    $batting_style = $_POST['batting_style'];
    $bowling_style = $_POST['bowling_style'];
    $total_matches_played = $_POST['total_matches_played'];
    $total_runs = $_POST['total_runs'];
    $total_wickets = $_POST['total_wickets'];
    $experience_level = $_POST['experience_level'];
    $achievements = $_POST['achievements'];

    // Handle photo upload
    if ($_FILES['photo']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);

        // Get the file extension
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a valid image (JPG or PNG)
        $allowed_extensions = array("jpg", "jpeg", "png");
        if (!in_array($imageFileType, $allowed_extensions)) {
            $error_message = "Error: Only JPG, JPEG, and PNG files are allowed.";
        } else {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $target_file;
            } else {
                $error_message = "Error: There was an error uploading your file.";
            }
        }
    }

    // Update data in the database if no errors
    if (empty($error_message)) {
        $sql = "UPDATE trainees SET 
                name = '$name', 
                age = '$age', 
                gender = '$gender', 
                email = '$email', 
                phone_number = '$phone_number', 
                role = '$role', 
                batting_style = '$batting_style', 
                bowling_style = '$bowling_style', 
                total_matches_played = '$total_matches_played', 
                total_runs = '$total_runs', 
                total_wickets = '$total_wickets', 
                photo = '$photo', 
                experience_level = '$experience_level', 
                achievements = '$achievements' 
                WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Trainee updated successfully!";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
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
        /* General Reset */
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
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-user-friends"></i> Players</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="Player_register.php">Add Player</a>
                <a class="dropdown-item" href="total_players.php">View Players</a>
                <a class="dropdown-item" href="edit_players.php">Manage Players</a>
                <a class="dropdown-item" href="manage_players.php">Manage Request</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-chart-line"></i> Player Performance</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="player_performance_metrics.php">Player Performance Metrics</a>
                <a class="dropdown-item" href="player_performance_analysis.php">Player Performance Analysis</a>
                <a class="dropdown-item" href="match_performance.php">Match Performance</a>
                <a class="dropdown-item" href="match_details.php">Match Details</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-user-graduate"></i> Trainee</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="trainee_registration.php">Add Trainee</a>
                <a class="dropdown-item" href="edit_trainee.php">Manage Trainee</a>
                <a class="dropdown-item" href="display_trainees.php">View Trainee</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-trophy"></i> Tournaments</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="tournament_details.php">Add Tournament</a>
                <a class="dropdown-item" href="tournament_list.php">View Tournaments</a>
            </div>
        </div>

        <a href="weekly_report.php"><i class="fas fa-file-alt"></i> Report</a>

        <a href="enquiries.php"><i class="fas fa-user-plus"></i> Enquiries</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Edit Trainee</h1>
        <div class="container">
            <?php if (!empty($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

           
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="form-container">
            <div class="form-group">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if ($gender == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <input type="text" name="role" value="<?php echo htmlspecialchars($role); ?>" required>
            </div>
            <div class="form-group">
                <label for="batting_style">Batting Style:</label>
                <input type="text" name="batting_style" value="<?php echo htmlspecialchars($batting_style); ?>">
            </div>
            <div class="form-group">
                <label for="bowling_style">Bowling Style:</label>
                <input type="text" name="bowling_style" value="<?php echo htmlspecialchars($bowling_style); ?>">
            </div>
            <div class="form-group">
                <label for="total_matches_played">Total Matches Played:</label>
                <input type="number" name="total_matches_played" value="<?php echo htmlspecialchars($total_matches_played); ?>">
            </div>
            <div class="form-group">
                <label for="total_runs">Total Runs:</label>
                <input type="number" name="total_runs" value="<?php echo htmlspecialchars($total_runs); ?>">
            </div>
            <div class="form-group">
                <label for="total_wickets">Total Wickets:</label>
                <input type="number" name="total_wickets" value="<?php echo htmlspecialchars($total_wickets); ?>">
            </div>
            <div class="form-group">
                <label for="experience_level">Experience Level:</label>
                <input type="text" name="experience_level" value="<?php echo htmlspecialchars($experience_level); ?>">
            </div>
            <div class="form-group">
                <label for="achievements">Achievements:</label>
                <textarea name="achievements"><?php echo htmlspecialchars($achievements); ?></textarea>
            </div>
            <div class="form-group">
                <label for="photo">Upload Photo:</label>
                <input type="file" name="photo" accept="image/jpeg, image/png">
                <p>Photo Path: <?php echo htmlspecialchars($photo); ?></p>
            </div>
            <div class="form-group">
                <button type="submit">Update Trainee</button>
            </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>