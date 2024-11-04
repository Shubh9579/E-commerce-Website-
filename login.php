<?php
// Start the session to manage user data
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "timeluxe"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $loginEmail = $_POST['loginEmail'];
    $loginPassword = $_POST['loginPassword'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?"); // Ensure you're using the correct table
    $stmt->bind_param("s", $loginEmail);

    // Execute the query
    $stmt->execute();
    $stmt->store_result();
    
    // Check if email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($loginPassword, $hashedPassword)) {
            // Start a session and redirect to the dashboard
            $_SESSION['email'] = $loginEmail; // Store the email in session
            header("Location: UserProfile/dashboard.php"); // Redirect to your dashboard page using a relative path
            exit(); // Ensure no further code is executed
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email.";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
