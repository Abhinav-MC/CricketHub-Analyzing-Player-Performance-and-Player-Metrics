<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Encrypt password
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);

    // Insert into database with 'pending' approval status
    $sql = "INSERT INTO players (name, username, password, phone, city, approval_status) 
            VALUES ('$name', '$username', '$password', '$phone', '$city', 'pending')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful! Your account is pending approval.'); window.location.href='register.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Registration - CricketHub</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #e0e5ec;
            color: #333;
        }

        .register-container {
            background: #e0e5ec;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 9px 9px 16px rgba(163, 177, 198, 0.6), -9px -9px 16px rgba(255, 255, 255, 0.5);
            text-align: center;
            width: 350px;
            position: relative;
            overflow: hidden;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        input[type="text"], 
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 10px;
            background: #e0e5ec;
            box-shadow: inset 6px 6px 10px rgba(163, 177, 198, 0.6), inset -6px -6px 10px rgba(255, 255, 255, 0.5);
            color: #333;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus, 
        input[type="password"]:focus {
            box-shadow: inset 4px 4px 8px rgba(163, 177, 198, 0.6), inset -4px -4px 8px rgba(255, 255, 255, 0.5);
        }

        input[type="submit"] {
            background: #e0e5ec;
            border: none;
            border-radius: 10px;
            color: #333;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 6px 6px 10px rgba(163, 177, 198, 0.6), -6px -6px 10px rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            margin-top: 20px;
            width: 100%;
        }

        input[type="submit"]:hover {
            box-shadow: inset 6px 6px 10px rgba(163, 177, 198, 0.6), inset -6px -6px 10px rgba(255, 255, 255, 0.5);
        }

        .login-link {
            margin-top: 20px;
            display: inline-block;
            color: #333;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Player Registration</h2>
        <form action="register.php" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="city" placeholder="City" required>
            <input type="submit" value="Register">
        </form>
        <a href="user_login.php" class="login-link">Already registered? Login here</a>
    </div>
</body>
</html>