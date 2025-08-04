<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists
        $sql_check = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':username', $username);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            echo "Username or Email already exists!";
        } else {
            // Prepare the SQL query to insert the user data
            $sql = "INSERT INTO users (full_name, email, username, password) VALUES (:full_name, :email, :username, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                $_SESSION['username'] = $username; // Set session variable
                header('Location: index.html'); // Redirect after successful registration
                exit;
            } else {
                echo "Error: Could not register the user!";
            }
        }
    }
}
?>
