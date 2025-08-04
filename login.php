<?php
session_start();  // Start the session to use session variables
include('connection.php');  // Include the database connection file

// Check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to find the user by username
    $sql = "SELECT * FROM users WHERE username = :username";

    // Use the $pdo variable instead of $conn
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if the user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['id'];  // Store user ID in session
            $_SESSION['username'] = $user['username'];  // Optionally store the username
            
            // Redirect the user to the home page or dashboard after successful login
            header('Location: index.php');  // You can change this to the page you want to redirect
            exit;  // Ensure no further code is executed after redirection
        } else {
            // Invalid password
            echo "Invalid username or password!";
        }
    } else {
        // Invalid username
        echo "Invalid username or password!";
    }
}
?>

<!-- HTML form for login -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
