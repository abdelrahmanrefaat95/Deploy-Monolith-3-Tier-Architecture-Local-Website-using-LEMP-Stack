<?php
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the login form
    $username = $_POST['username'];
    $inputPassword = $_POST['password'];
    
    // Database connection parameters
    $servername = "servername"; 
    $db_username = "username";
    $db_password = "password";
    $database = "dbname";
    $ssl_ca = "ssl cert location";
    
    // Create connection with SSL
    $conn = mysqli_init();
    $conn->ssl_set(NULL, NULL, $ssl_ca, NULL, NULL);
    $conn->real_connect($servername, $db_username, $db_password, $database, 3306, NULL, MYSQLI_CLIENT_SSL);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
      die("Error executing query: " . $conn->error);
    }


    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify the password using password_verify
        if (password_verify($inputPassword, $hashedPassword)) {
            // Authentication successful, redirect to welcome.php and pass the username as a query parameter
            header("Location: welcome.php?username=" . urlencode($username));
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>
