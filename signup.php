<?php


// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "travel_db", port: 3307);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['user']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['num']);
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.location.href='signup.html';</script>";
        exit;
    }
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo "<script>alert('Phone number must be 10 digits!'); window.location.href='signup.html';</script>";
        exit;
    }
    if ($pass !== $cpass) {
        echo "<script>alert('Passwords do not match!'); window.location.href='signup.html';</script>";
        exit;
    }

    // Hash password
    $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

    // Prepared statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user, $email, $phone, $hashed_pass);

    try {
        $stmt->execute();
        echo "<script>
                alert('Registration successful! Please log in.');
                window.location.href='login.html';
              </script>";
    } catch (mysqli_sql_exception $e) {
        if ($conn->errno === 1062) {
            echo "<script>alert('Email already exists! Please use another one.'); window.location.href='signup.html';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='signup.html';</script>";
        }
    }

    $stmt->close();
}

$conn->close();
