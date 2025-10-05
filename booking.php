<?php
$servername = "localhost";
$username = "root";
$password = "";   // keep empty if you set root with no password
$dbname = "travel_db";
$port = 3307;     // ⚠️ use 3306 if your MySQL runs on 3306

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $ptype = $_POST['Ptype'];
    
    // Decide package based on selected type
    if ($ptype === "International") {
        $package = $_POST['Package1'];
    } else {
        $package = $_POST['Package2'];
    }

    $num_people = $_POST['No_of_ppl'];
    $travel_date = $_POST['TravelDate'];

    // Prepared statement to prevent SQL injection
    $sql = "INSERT INTO bookings (fname, lname, phone, email, package_type, package_name, no_of_people, travel_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssis", $fname, $lname, $phone, $email, $ptype, $package, $num_people, $travel_date);

    if ($stmt->execute()) {
        echo "<script>alert('Booking Successful!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Booking Failed!');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
