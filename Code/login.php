<?php
// Get user input from the form
$email = $_POST['email'];
$password = $_POST['password'];

// Database connection parameters
$host = "localhost";
$dbUserName = "root";
$dbPassword = "";
$dbname = "login_data";

// Create a new MySQLi connection
$con = new mysqli($host, $dbUserName, $dbPassword, $dbname);

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} else {
    // Check if the email already exists in the database
    $stmt = $con->prepare("SELECT * FROM register WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute(); // Correct function name
    $stmt_result = $stmt->get_result();

    // If the email is found, check the password
    if ($stmt_result->num_rows > 0) {
        $data = $stmt_result->fetch_assoc();
        // Compare passwords
        if ($data['password'] === $password) {
            echo "<h2>Login Successfully</h2>";
        } else {
            echo "<h2>Invalid Email or Password</h2>";
        }
    } else {
        // If the email doesn't exist, insert a new user
        $stmt = $con->prepare("INSERT INTO register (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);
        if ($stmt->execute()) {
            echo "<h2>Registration Successful</h2>";
        } else {
            echo "<h2>Error in Registration</h2>";
        }
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
}
?>