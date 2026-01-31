<?php
// MariaDB database connection settings
$servername = "rdsDNS"; // Change this to the IP address of your MariaDB server
$username = "username"; // Change this to your MariaDB username
$password = "password"; // Change this to your MariaDB password
$database = "dbname"; // Change this to your MariaDB database name
$ssl_ca = "SSLCertLocation";

// Create connection with SSL
$conn = mysqli_init();

// Set SSL CA certificate
$conn->ssl_set(NULL, NULL, $ssl_ca, NULL, NULL);

// Connect with SSL flag
$conn->real_connect($servername, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $category = $_POST["category"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Prepare the SQL statement to avoid SQL injection
    $query = $conn->prepare("INSERT INTO users (username, password, category) VALUES (?, ?, ?)");
    $query->bind_param("sss", $username, $hashedPassword, $category);

    if ($query->execute()) {
        header("Location: login.html"); // Redirect to the login page
        exit();
    } else {
        echo "Registration failed.";
    }

    $query->close();
}

$conn->close();
?>
