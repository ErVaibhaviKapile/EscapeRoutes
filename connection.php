<?php
$servername = "localhost"; // Database host
$username = "root";        // Database username
$password = "";            // Database password (empty by default in XAMPP)
$dbname = "tour";          // Database name

try {
    // Create PDO instance and establish a connection to the database
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Uncomment below to test connection (remove in production)
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    // Display error message if connection fails
    die("Connection failed: " . $e->getMessage());
}
?>
