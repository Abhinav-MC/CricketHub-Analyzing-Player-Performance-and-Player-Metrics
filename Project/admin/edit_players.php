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
$player_id = null;
$full_name = $username = $age = $city = $gender = $phone_number = $email = $player_role = $batting_style = $bowling_style = $experience_level = $team_name = $achievements = $photo_url = "";

// Check if the player ID is provided in the URL
if (isset($_GET['id'])) {
    $player_id = $_GET['id'];

    // Fetch player details from the database
    $sql = "SELECT * FROM player_registration WHERE player_id = '$player_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $full_name = $row['full_name'];
        $username = $row['username'];
        $age = $row['age'];
        $city = $row['city'];
        $gender = $row['gender'];
        $phone_number = $row['phone_number'];
        $email = $row['email'];
        $player_role = $row['player_role'];
        $batting_style = $row['batting_style'];
        $bowling_style = $row['bowling_style'];
        $experience_level = $row['experience_level'];
        $team_name = $row['team_name'];
        $achievements = $row['achievements'];
        $photo_url = $row['photo_url'];
    } else {
        echo "<p style='color: red;'>Error: Player not found!</p>";
        $conn->close();
        exit();
    }
}

// Handle form submission for updating player details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $age = (int)$_POST['age'];
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : null;
    $player_role = mysqli_real_escape_string($conn, $_POST['player_role']);
    $batting_style = mysqli_real_escape_string($conn, $_POST['batting_style']);
    $bowling_style = mysqli_real_escape_string($conn, $_POST['bowling_style']);
    $experience_level = mysqli_real_escape_string($conn, $_POST['experience_level']);
    $team_name = isset($_POST['team_name']) ? mysqli_real_escape_string($conn, $_POST['team_name']) : null;
    $achievements = isset($_POST['achievements']) ? mysqli_real_escape_string($conn, $_POST['achievements']) : null;

    // Handle photo upload (optional)
    $photo_url = $row['photo_url']; // Keep the existing photo URL if no new photo is uploaded
    $uploadOk = 1;
    $target_dir = "uploads/"; // Folder where photos will be saved

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_file = $target_dir . uniqid() . '_' . basename($_FILES["photo"]["name"]); // Unique file name
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        if (getimagesize($_FILES["photo"]["tmp_name"]) === false) {
            $uploadOk = 0;
            $upload_error = "File is not an image.";
        }

        // Check file size (max 5MB)
        if ($_FILES["photo"]["size"] > 5000000) {
            $uploadOk = 0;
            $upload_error = "File size exceeds the maximum limit of 5MB.";
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
            $upload_error = "Only JPG, JPEG, and PNG files are allowed.";
        }

        // If no errors, try uploading the file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo_url = $target_file;  // Update the photo URL in the database
            } else {
                $upload_error = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update player details in the database
    $sql_update = "UPDATE player_registration SET
                   full_name = '$full_name',
                   username = '$username',
                   age = '$age',
                   city = '$city',
                   gender = '$gender',
                   phone_number = '$phone_number',
                   email = '$email',
                   player_role = '$player_role',
                   batting_style = '$batting_style',
                   bowling_style = '$bowling_style',
                   experience_level = '$experience_level',
                   team_name = '$team_name',
                   achievements = '$achievements',
                   photo_url = '$photo_url'
                   WHERE player_id = '$player_id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<div class='success-message'>Player details updated successfully!</div>";
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }

    // Display upload error if any
    if (isset($upload_error)) {
        echo "<p style='color: red;'>Error: $upload_error</p>";
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
        <h1>Edit Player Details</h1>

        <form method="POST" enctype="multipart/form-data" class="form-container">
            <!-- Full Name -->
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>" required>
            </div>

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>

            <!-- Age -->
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $age; ?>" required>
            </div>

            <!-- City -->
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $city; ?>" required>
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male" <?php if ($gender == "Male") echo "selected"; ?>>Male</option>
                    <option value="Female" <?php if ($gender == "Female") echo "selected"; ?>>Female</option>
                    <option value="Other" <?php if ($gender == "Other") echo "selected"; ?>>Other</option>
                </select>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?php echo $phone_number; ?>" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email (Optional):</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            </div>

            <!-- Player Role -->
            <div class="form-group">
                <label for="player_role">Player Role:</label>
                <select id="player_role" name="player_role">
                    <option value="Batsman" <?php if ($player_role == "Batsman") echo "selected"; ?>>Batsman</option>
                    <option value="Bowler" <?php if ($player_role == "Bowler") echo "selected"; ?>>Bowler</option>
                    <option value="All-rounder" <?php if ($player_role == "All-rounder") echo "selected"; ?>>All-rounder</option>
                    <option value="Wicket-keeper" <?php if ($player_role == "Wicket-keeper") echo "selected"; ?>>Wicketkeeper</option>
                </select>
            </div>

            <!-- Batting Style -->
            <div class="form-group">
                <label for="batting_style">Batting Style:</label>
                <select id="batting_style" name="batting_style">
                    <option value="Right-hand batsman" <?php if ($batting_style == "Right-hand batsman") echo "selected"; ?>>Right-hand batsman</option>
                    <option value="Left-hand batsman" <?php if ($batting_style == "Left-hand batsman") echo "selected"; ?>>Left-hand batsman</option>
                </select>
            </div>

            <!-- Bowling Style -->
            <div class="form-group">
                <label for="bowling_style">Bowling Style:</label>
                <select id="bowling_style" name="bowling_style">
                    <option value="Right-arm fast" <?php if ($bowling_style == "Right-arm fast") echo "selected"; ?>>Right-arm fast</option>
                    <option value="Left-arm fast" <?php if ($bowling_style == "Left-arm fast") echo "selected"; ?>>Left-arm fast</option>
                    <option value="Right-arm Medium" <?php if ($bowling_style == "Right-arm Medium") echo "selected"; ?>>Right-arm Medium</option>
                    <option value="Right-arm off-spin" <?php if ($bowling_style == "Right-arm off-spin") echo "selected"; ?>>Right-arm off-spin</option>
                    <option value="Right-arm leg-spin" <?php if ($bowling_style == "Right-arm leg-spin") echo "selected"; ?>>Right-arm leg-spin</option>
                    <option value="Left-arm leg-spin" <?php if ($bowling_style == "Left-arm leg-spin") echo "selected"; ?>>Left-arm leg-spin</option>
                    <option value="Left-arm chinaman" <?php if ($bowling_style == "Left-arm chinaman") echo "selected"; ?>>Left-arm chinaman</option>                
                </select>
            </div>

            <!-- Experience Level -->
            <div class="form-group">
                <label for="experience_level">Experience Level:</label>
                <select id="experience_level" name="experience_level" required>
                    <option value="Beginner" <?php if ($experience_level == "Beginner") echo "selected"; ?>>Beginner</option>
                    <option value="Intermediate" <?php if ($experience_level == "Intermediate") echo "selected"; ?>>Intermediate</option>
                    <option value="Advanced" <?php if ($experience_level == "Advanced") echo "selected"; ?>>Advanced</option>
                </select>
            </div>

            <!-- Team Name -->
            <div class="form-group">
                <label for="team_name">Team Name (Optional):</label>
                <input type="text" id="team_name" name="team_name" value="<?php echo $team_name; ?>">
            </div>

            <!-- Achievements -->
            <div class="form-group">
                <label for="achievements">Achievements (Optional):</label>
                <textarea id="achievements" name="achievements"><?php echo $achievements; ?></textarea>
            </div>

            <!-- Player Photo -->
            <div class="form-group">
                <label for="photo">Player Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <?php if ($photo_url) { ?>
                    <img src="<?php echo $photo_url; ?>" alt="Player Photo" style="max-width: 200px; margin-top: 10px;">
                <?php } ?>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Update Player</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>