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

// Fetch trainee data from the database
$sql = "SELECT name, role, experience_level, achievements, photo FROM trainees";
$result = $conn->query($sql);

$trainees = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trainees[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainees - Cricket Club</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #FFFFFF; /* White background */
            font-family: 'Poppins', sans-serif;
            color: #333333; /* Dark text for better readability */
        }

        .navbar {
            background-color: #1B2735;
            padding: 15px;
            border-bottom: 2px solid #2DE1FC;
        }

        .trainee-section {
            padding: 50px 20px;
        }

        .trainee-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #1B2735; /* Dark blue for headings */
            text-align: center;
        }

        .trainee-card {
            background: #F9FAFB;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .trainee-card:hover {
            transform: translateY(-5px);
        }

        .trainee-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid #2DE1FC; /* Blue border for images */
        }

        .trainee-card h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: #FFC107; /* Yellow for trainee names */
        }

        .trainee-card p {
            font-size: 1rem;
            color: #666666; /* Gray for trainee details */
        }

        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 10px 20px;
            background: #1B2735;
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background: #2DE1FC;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #666666; /* Gray for footer text */
        }
    </style>
</head>
<body>

    <!-- Custom Navbar -->
    <nav class="bg-indigo-600 text-white py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="text-2xl font-bold">CricketHub</div>
            <ul class="flex space-x-6">
                <li><a href="index.html" class="hover:underline">Home</a></li>
                <li><a href="about.html" class="hover:underline">About Us</a></li>
                <li><a href="contact.html" class="hover:underline">Contact Us</a></li>
                <li><a href="trainees.php" class="hover:underline">Trainees</a></li>
                <li><a href="register.php" class="hover:underline">Sign Up</a></li>
                <li><a href="user_login.php" class="hover:underline">User Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Trainee Section -->
    <section class="trainee-section">
        <h2>Our Trainees</h2>

        <!-- Search Bar -->
        

        <!-- Trainee Cards -->
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="traineeList">
                <?php foreach ($trainees as $trainee): ?>
                    <div class="trainee-card">
                        <!-- Use the correct image path for the user side -->
                        <img src="/Project/admin/<?php echo $trainee['photo']; ?>" 
                             alt="<?php echo $trainee['name']; ?>">
                        
                        <!-- Debugging: Print the full image URL -->
                        

                        <h3><?php echo $trainee['name']; ?></h3>
                        <p><strong>Role:</strong> <?php echo $trainee['role']; ?></p>
                        <p><strong>Experience:</strong> <?php echo $trainee['experience_level']; ?> </p>
                        <p><strong>Achievements:</strong> <?php echo $trainee['achievements']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center mt-8">
        © 2025 Cricket Club 
    </footer>

    <!-- JavaScript for Search Functionality -->
    <script>
        function searchTrainees() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const trainees = document.querySelectorAll('.trainee-card');

            trainees.forEach(trainee => {
                const name = trainee.querySelector('h3').textContent.toLowerCase();
                const role = trainee.querySelector('p:nth-child(3)').textContent.toLowerCase();

                if (name.includes(input) || role.includes(input)) {
                    trainee.style.display = 'block';
                } else {
                    trainee.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>