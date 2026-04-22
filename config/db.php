<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quiz_system";

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if(!$conn){
    die("Connection Failed: ".mysqli_connect_error());
}

/**
 * Sanitize user input to prevent XSS and basic SQL injection
 */
if (!function_exists('sanitize')) {
    function sanitize($data) {
        global $conn;
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        // This line is crucial for database security
        return mysqli_real_escape_string($conn, $data);
    }
}
?>