<?php
session_start();
include 'connection.php'; // Include your PDO connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    // Prepare the SQL query to insert the contact form data
    $sql = "INSERT INTO contacts (name, email, phone, message) VALUES (:name, :email, :phone, :message)";
    
    // Use the PDO connection ($pdo) instead of $conn
    $stmt = $pdo->prepare($sql);
    
    // Bind form data to the query
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':message', $message);
    
    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "<script>alert('Error: Could not send the message!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(to bottom right, #badeec, #23cae0);
      margin: 0;
      padding: 0;
      color: #333;
    }

    .contact-form-container {
      background: rgba(255, 255, 255, 0.85);
      max-width: 500px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .contact-form-container h1 {
      text-align: center;
      margin-bottom: 15px;
      color: #181313;
      font-size: 2.5rem;
      font-weight: bold;
    }

    .contact-form-container p {
      text-align: center;
      margin-bottom: 20px;
      color: #666;
      font-size: 1rem;
    }

    .contact-form .form-group {
      margin-bottom: 15px;
    }

    .contact-form label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #444;
    }

    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      background: #f9f9f9;
      transition: all 0.3s ease;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
      outline: none;
      border-color: #0f0d0d;
      box-shadow: 0 0 10px rgba(255, 111, 97, 0.5);
    }

    .contact-form .submit-button {
      background: linear-gradient(to right, #55a033, #40c0ba);
      color: #fff;
      border: none;
      padding: 15px 20px;
      font-size: 18px;
      border-radius: 8px;
      cursor: pointer;
      display: block;
      width: 100%;
      transition: transform 0.3s ease, background 0.3s ease;
    }

    .contact-form .submit-button:hover {
      background: linear-gradient(to right, #5e4f4e, #928685);
      transform: translateY(-3px);
    }

    .contact-form .submit-button:active {
      transform: translateY(1px);
    }
  </style>
</head>
<body>
  <div class="contact-form-container">
    <h1>Contact Us</h1>
    <p>Have questions? We’re here to help!</p>
    <form action="" method="post" class="contact-form">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Your Name" required>
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" placeholder="Your Phone Number">
      </div>
      <div class="form-group">
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
      </div>
      <button type="submit" class="submit-button">Send Message</button>
    </form>
  </div>
</body>
</html>
