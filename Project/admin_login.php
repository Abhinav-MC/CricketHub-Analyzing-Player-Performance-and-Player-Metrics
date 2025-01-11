<?php
session_start();

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

// Check if the admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin/admin_dashboard.php"); // Redirect to the dashboard
    exit();
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debug: Print the submitted username and password
    echo "<p>Submitted Username: $username</p>";
    echo "<p>Submitted Password: $password</p>";

    // Fetch admin credentials from the database
    $sql = "SELECT id, username, password FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        // Debug: Print the fetched admin data
        

        // Verify the plaintext password
        if ($admin && $password === $admin['password']) {
            // Set session variable to indicate admin is logged in
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin/admin_dashboard.php"); // Redirect to the dashboard
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        // Debug: Print SQL error
        $error = "SQL Error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CricketHub</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #1a1a1a; /* Dark background */
            color: #fff;
        }

        .container {
            background: #262626; /* Dark container background */
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 350px;
            border: 1px solid #444;
        }

        h1 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            color: #fff;
        }

        p {
            font-size: 1rem;
            color: #aaa;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 1rem;
            color: #ccc;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 6px;
            background: #333;
            color: #fff;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-group input::placeholder {
            color: #888;
        }

        .form-group input:focus {
            border-color: #00bcd4; /* Light blue focus effect */
        }

        .btn {
            background: #00bcd4; /* Light blue button */
            border: none;
            border-radius: 6px;
            color: #fff;
            padding: 12px 24px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            background: #0097a7; /* Darker blue on hover */
        }

        .error {
            color: #e74c3c; /* Red error message */
            margin: 10px 0;
            font-size: 0.9rem;
        }

        footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #aaa;
        }

        footer a {
            color: #00bcd4; /* Light blue link */
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        <p>Welcome back! Please enter your credentials.</p>

        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <footer>
            <p>Don't have access? <a href="contact_admin.php">Contact Admin</a></p>
        </footer>
    </div>
</body>
</html>