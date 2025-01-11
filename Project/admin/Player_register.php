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

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
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

    // Handle file upload
    $photo_url = null;
    if (isset($_FILES['photo_url']) && $_FILES['photo_url']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo_url"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["photo_url"]["tmp_name"]);
        if ($check !== false) {
            // Allow certain file formats
            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["photo_url"]["tmp_name"], $target_file)) {
                    $photo_url = $target_file;
                } else {
                    $error_message = "Error uploading file.";
                }
            } else {
                $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            $error_message = "File is not an image.";
        }
    }

    if (empty($error_message)) {
        // Check if the username exists in the players table
        $check_username_sql = "SELECT id FROM players WHERE username = '$username'";
        $result = $conn->query($check_username_sql);

        if ($result->num_rows == 0) {
            $error_message = "Error: Username '$username' does not exist. Please ensure the user has already registered with basic details.";
        } else {
            $row = $result->fetch_assoc();
            $player_id = $row['id'];

            // Insert detailed registration data into the player_registration table
            $sql_registration = "INSERT INTO player_registration (player_id, full_name, username, age, city, gender, phone_number, email, player_role, batting_style, bowling_style, experience_level, team_name, achievements, photo_url)
                                 VALUES ('$player_id', '$full_name', '$username', '$age', '$city', '$gender', '$phone_number', '$email', '$player_role', '$batting_style', '$bowling_style', '$experience_level', '$team_name', '$achievements', '$photo_url')";

            if ($conn->query($sql_registration) === TRUE) {
                $success_message = "Player registered successfully with detailed information!";
            } else {
                $error_message = "Error: " . $sql_registration . "<br>" . $conn->error;
            }
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
        }

        .error-message {
            background-color: #f8d7da; /* Red background */
            color: #721c24; /* Dark red text */
            padding: 15px;
            border: 1px solid #f5c6cb; /* Light red border */
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
    <script>
        // JavaScript to display the selected file name
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('photo_url');
            const fileLabel = document.querySelector('label[for="photo_url"]');

            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    fileLabel.textContent = `Player Photo: ${fileInput.files[0].name}`;
                } else {
                    fileLabel.textContent = 'Player Photo:';
                }
            });
        });
    </script>
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
        <h1>Register a New Player</h1>

        <form action="player_register.php" method="POST" enctype="multipart/form-data" class="form-container">
            <!-- Player ID (Hidden Field) -->
            <input type="hidden" id="player_id" name="player_id">

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required oninput="fetchPlayerDetails(this.value)">
            </div>

            <!-- Full Name -->
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Age -->
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>

            <!-- City -->
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email (Optional):</label>
                <input type="email" id="email" name="email">
            </div>

            <!-- Player Role -->
            <div class="form-group">
                <label for="player_role">Player Role:</label>
                <select id="player_role" name="player_role" required>
                    <option value="Batsman">Batsman</option>
                    <option value="Bowler">Bowler</option>
                    <option value="All-rounder">All-rounder</option>
                    <option value="Wicket-keeper">Wicket-keeper</option>
                </select>
            </div>

            <!-- Batting Style -->
            <div class="form-group">
                <label for="batting_style">Batting Style:</label>
                <select id="batting_style" name="batting_style" required>
                    <option value="Right-hand batsman">Right-hand batsman</option>
                    <option value="Left-hand batsman">Left-hand batsman</option>
                </select>
            </div>

            <!-- Bowling Style -->
            <div class="form-group">
                <label for="bowling_style">Bowling Style:</label>
                <select id="bowling_style" name="bowling_style" required>
                    <option value="Right-arm fast">Right-arm fast</option>
                    <option value="Left-arm fast">Left-arm fast</option>
                    <option value="Right-arm Medium">Right-arm Medium</option>
                    <option value="Right-arm off-spin">Right-arm off-spin</option>
                    <option value="Right-arm leg-spin">Right-arm leg-spin</option>
                    <option value="Left-arm leg-spin">Left-arm leg-spin</option>
                    <option value="Left-arm chinaman">Left-arm chinaman</option>
                </select>
            </div>

            <!-- Experience Level -->
            <div class="form-group">
                <label for="experience_level">Experience Level:</label>
                <select id="experience_level" name="experience_level" required>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>
            </div>

            <!-- Team Name -->
            <div class="form-group">
                <label for="team_name">Team Name (Optional):</label>
                <input type="text" id="team_name" name="team_name">
            </div>

            <!-- Achievements -->
            <div class="form-group">
                <label for="achievements">Achievements (Optional):</label>
                <textarea id="achievements" name="achievements"></textarea>
            </div>

            <!-- Player Photo -->
            <div class="form-group">
                <label for="photo_url">Player Photo:</label>
                <input type="file" id="photo_url" name="photo_url" accept="image/*">
            </div>

            <?php if (!empty($success_message)) { ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php } ?>

            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php } ?>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Register Player</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // JavaScript to fetch player details when username is entered
        function fetchPlayerDetails(username) {
            if (username) {
                // Fetch player details from the server
                fetch(`fetch_player_details.php?username=${username}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            // Display error message if no player is found
                            alert(data.error);
                        } else {
                            // Populate the fields with fetched data
                            document.getElementById('full_name').value = data.name;
                            document.getElementById('city').value = data.city;
                            document.getElementById('phone_number').value = data.phone;
                            document.getElementById('player_id').value = data.id;
                        }
                    })
                    .catch(error => console.error('Error fetching player details:', error));
            }
        }
    </script>
</body>
</html>