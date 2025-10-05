<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";   // default in XAMPP
$password = "";       // default is empty
$dbname = "travel_db"; // make sure this is the same DB you created

$conn = new mysqli($servername, $username, $password, $dbname, port: 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $conn->real_escape_string($_POST['pass']);

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($pass, $user['password'])) {
            // Save login info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            echo "<script>
                    alert('Login successful! Welcome, " . $user['name'] . "');
                    window.location.href='index.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Invalid password. Please try again.');
                    window.location.href='login.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('No user found with that email.');
                window.location.href='login.html';
              </script>";
    }
}

$conn->close();
?>
