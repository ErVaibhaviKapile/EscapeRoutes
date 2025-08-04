<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include your PDO connection file
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize form data
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $destination = trim($_POST['destination']);
    $departureDate = $_POST['departureDate'];
    $returnDate = $_POST['returnDate'];
    $flightClass = $_POST['flightClass'];
    $hotel = $_POST['hotel'];
    $hotelRating = $_POST['hotelRating'];
    $paymentMethod = $_POST['paymentMethod'];
    $cardNumber = $_POST['cardNumber'];
    $cvv = $_POST['cvv'];
    $expiryDate = $_POST['expiryDate'];
    $notes = trim($_POST['notes']);
    $numberOfGuests = $_POST['numberOfGuests'];

    // Handle the uploaded payment screenshot
    $paymentScreenshot = '';
    if (isset($_FILES['paymentScreenshot']) && $_FILES['paymentScreenshot']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES['paymentScreenshot']['name']);
        move_uploaded_file($_FILES['paymentScreenshot']['tmp_name'], $targetFile);
        $paymentScreenshot = $targetFile;
    }

    // Prepare the SQL query to insert the booking data
    $sql = "INSERT INTO new_booking (full_name, email, phone, destination, departure_date, return_date, flight_class, hotel, hotel_rating, payment_method, card_number, cvv, expiry_date, notes, number_of_guests, payment_screenshot)
            VALUES (:full_name, :email, :phone, :destination, :departure_date, :return_date, :flight_class, :hotel, :hotel_rating, :payment_method, :card_number, :cvv, :expiry_date, :notes, :number_of_guests, :payment_screenshot)";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters to prevent SQL injection
    $stmt->bindParam(':full_name', $fullName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':destination', $destination);
    $stmt->bindParam(':departure_date', $departureDate);
    $stmt->bindParam(':return_date', $returnDate);
    $stmt->bindParam(':flight_class', $flightClass);
    $stmt->bindParam(':hotel', $hotel);
    $stmt->bindParam(':hotel_rating', $hotelRating);
    $stmt->bindParam(':payment_method', $paymentMethod);
    $stmt->bindParam(':card_number', $cardNumber);
    $stmt->bindParam(':cvv', $cvv);
    $stmt->bindParam(':expiry_date', $expiryDate);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':number_of_guests', $numberOfGuests);
    $stmt->bindParam(':payment_screenshot', $paymentScreenshot);

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Booking successfully submitted!');</script>";
    } else {
        echo "<script>alert('Error: Could not submit the booking!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour and Travel Booking Form</title>
    <style>
    
    /* Reset margin and padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Set background gradient and centralize the form */
    body {
        font-family: 'Roboto', sans-serif;
        background: linear-gradient(to right, #c9a4ca, #bd87b1);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        color: #946d6d;
    }

    /* Main container for the form */
    .container {
        background: #fdf9f9;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 900px; /* Adjusted for better width */
        padding: 40px;
        overflow: hidden;
    }

    /* Title of the form */
    h2 {
        text-align: center;
        font-size: 2.5rem;
        color: #141313;
        margin-bottom: 30px;
    }

    /* Style for each form section */
    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        font-size: 1.6rem;
        margin-bottom: 15px;
        color: #0c0a0a;
    }

    /* Group form elements with proper alignment */
    .form-group {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    /* Input field and select styling */
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px;
        font-size: 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #f1f1f1;
        transition: all 0.3s ease;
    }

    /* Make inputs and selects responsive */
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: calc(50% - 10px); /* Fix for spacing in a 2-column layout */
    }

    /* Input fields focus effect */
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #00b4db;
        background: #e8f7ff;
        box-shadow: 0 0 5px rgba(0, 180, 219, 0.5);
        outline: none;
    }

    /* Textarea styling */
    .form-group textarea {
        resize: vertical;
        min-height: 120px;
        width: 100%;
    }

    /* Buttons */
    .btn {
        display: inline-block;
        width: 100%;
        padding: 15px;
        background: linear-gradient(to right, #79888b, #1c2f35);
        color: rgb(12, 11, 11);
        font-size: 1.2rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .btn:hover {
        background: linear-gradient(to right, #0083b0, #00b4db);
        transform: translateY(-3px);
    }
    
    .btn:active {
        transform: translateY(1px);
    }

    /* Responsive design for smaller screens */
    @media (max-width: 768px) {
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%; /* Full-width for small screens */
        }

        .container {
            padding: 20px; /* Adjust container padding */
        }
    }


    </style>
</head>
<body>

    <div class="container">
        <h2>Booking Form</h2>
        
        <form method="post" action="" enctype="multipart/form-data">

            <!-- Personal Information Section -->
            <div class="form-section">
                <h3>Personal Information</h3>
                <div class="form-group">
                    <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                    <input type="text" id="destination" name="destination" placeholder="Enter your travel destination" required>
                </div>
            </div>

            <!-- Travel Dates Section -->
            <div class="form-section">
                <h3>Travel Dates</h3>
                <div class="form-group">
                    <input type="date" id="departureDate" name="departureDate" required>
                    <input type="date" id="returnDate" name="returnDate" required>
                </div>
            </div>

            <!-- Guests Section -->
            <div class="form-section">
                <h3>Number of Guests</h3>
                <div class="form-group">
                    <input type="number" id="numberOfGuests" name="numberOfGuests" placeholder="Enter number of guests" min="1" required>
                </div>
            </div>

            <!-- Flight and Hotel Section -->
            <div class="form-section">
                <h3>Flight and Hotel Information</h3>
                <div class="form-group">
                    <select id="flightClass" name="flightClass" required>
                        <option value="economy">Economy</option>
                        <option value="business">Business</option>
                        <option value="firstClass">First Class</option>
                    </select>
                    <select id="hotel" name="hotel" required>
                        <option value="yes">Hotel Required</option>
                        <option value="no">No Hotel</option>
                    </select>
                </div>
                <div class="form-group">
                    <select id="hotelRating" name="hotelRating" required>
                        <option value="3">3-Star</option>
                        <option value="4">4-Star</option>
                        <option value="5">5-Star</option>
                    </select>
                </div>
            </div>

            <!-- Payment Information Section -->
            <div class="form-section">
                <h3>Payment Information</h3>
                <div class="form-group">
                    <select id="paymentMethod" name="paymentMethod" required>
                        <option value="creditCard">Credit Card</option>
                        <option value="debitCard">Debit Card</option>
                        <option value="paypal">PayPal</option>
                    </select>
                    <input type="text" id="cardNumber" name="cardNumber" placeholder="Card Number" required>
                </div>
                <div class="form-group">
                    <input type="text" id="cvv" name="cvv" placeholder="CVV" required>
                    <input type="month" id="expiryDate" name="expiryDate" required>
                </div>
            </div>

            <!-- Payment Screenshot Section -->
            <div class="form-section">
                <h3>Payment Screenshot</h3>
                <div class="form-group">
                    <input type="file" id="paymentScreenshot" name="paymentScreenshot" accept="image/*" required>
                </div>
            </div>

            <!-- Additional Notes Section -->
            <div class="form-section">
                <h3>Additional Notes</h3>
                <textarea id="notes" name="notes" placeholder="Enter any special requests or notes"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-section">
                <button type="submit" class="btn">Submit Booking</button>
            </div>
        </form>
    </div>

</body>
</html>
