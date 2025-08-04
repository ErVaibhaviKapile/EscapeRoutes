<?php
// Include database connection
include 'connection.php';

// Check if the destination_name is passed via the URL query string
if (isset($_GET['destination_name']) && !empty($_GET['destination_name'])) {
    $destination_name = $_GET['destination_name'];

    // Prepare the query to fetch the destination details from the destinations table
    $destination_query = "SELECT * FROM destinations WHERE name = :destination_name LIMIT 1";
    $stmt = $pdo->prepare($destination_query);
    $stmt->bindParam(':destination_name', $destination_name, PDO::PARAM_STR);

    // Execute the query and check if it succeeds
    if ($stmt->execute()) {
        // Check if destination data exists
        if ($stmt->rowCount() > 0) {
            $destination_data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $destination_data = null;
            $error_message = "Destination not found. Please make sure the destination name is correct.";
        }
    } else {
        // Query execution failed
        $destination_data = null;
        $error_message = "Error fetching destination details from the database.";
    }
} else {
    // If destination_name parameter is not provided, show an error
    $error_message = "No destination parameter provided. Please go back and try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destination Details - <?php echo htmlspecialchars($destination_name ?? 'Error'); ?></title>
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 1rem;
        }

        .destination-details {
            max-width: 800px;
            margin: 2rem auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .destination-card {
            padding: 1.5rem;
        }

        .destination-card h1 {
            background-color: #17a2b8;
            color: white;
            padding: 0.5rem;
            border-radius: 5px;
            text-align: center;
        }

        .destination-card img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
            margin: 1rem 0;
        }

        .destination-card h2 {
            margin-top: 1rem;
            font-size: 1.2rem;
            color: #007bff;
        }

        .destination-card p {
            margin: 0.5rem 0;
            line-height: 1.6;
        }

        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }

        /* Error Message Styling */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 20px auto;
            border-radius: 6px;
            border: 1px solid #f5c6cb;
            max-width: 800px;
        }

        .buy-now-container {
            text-align: center;
            margin-top: 2rem;
        }

        button {
            display: inline-block;
            padding: 0.8rem 2rem;
            font-size: 1.2rem;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Pinkish Button Styling */
        button.book-now {
            background-color: #e91e63;
        }

        button.book-now:hover {
            background-color: #d81b60;
        }
    </style>
</head>
<body>
    <header>
        <!-- Your header content here -->
    </header>

    <section class="destination-details">
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php elseif ($destination_data): ?>
            <div class="destination-card">
    <h1><?php echo htmlspecialchars($destination_data['name']); ?></h1>
    <img src="admin/uploads/<?php echo htmlspecialchars(basename($destination_data['image_url'])); ?>" alt="Destination Image">
    
    <h2>Description</h2>
    <p><?php echo nl2br(htmlspecialchars($destination_data['description'])); ?></p>
    
    <h2>Attractions</h2>
    <?php
    if (!empty($destination_data['attractions'])) {
        $attractions = explode(',', $destination_data['attractions']);
        echo '<ul>';
        foreach ($attractions as $attraction) {
            echo '<li>' . htmlspecialchars(trim($attraction)) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No attractions available.</p>';
    }
    ?>
    
    <h2>Special Attractions</h2>
    <p><?php echo nl2br(htmlspecialchars($destination_data['special_attractions'])); ?></p>
    
    <h2>Extra Information</h2>
    <p><?php echo nl2br(htmlspecialchars($destination_data['extra_info'])); ?></p>
    
    <h2>Package Name</h2>
    <p><?php echo htmlspecialchars($destination_data['package_name']); ?></p>
    
    <h2>Duration</h2>
    <p><?php echo htmlspecialchars($destination_data['duration']); ?></p>
    
    <h2>Price</h2>
    <p class="price"><?php echo '$' . number_format($destination_data['price'], 2); ?></p>
    
    <h2>Includes</h2>
    <p><?php echo nl2br(htmlspecialchars($destination_data['includes'])); ?></p>
    
    <h2>Created At</h2>
    <p><?php echo htmlspecialchars($destination_data['created_at']); ?></p>

    <h2>Updated At</h2>
    <p><?php echo htmlspecialchars($destination_data['updated_at']); ?></p>

    <div class="buy-now-container">
    <a href="book.php?destination_id=<?php echo $destination_data['id']; ?>">
        <button class="book-now">Buy Now</button>
    </a>
</div>

</div>

        <?php else: ?>
            <p>Destination details not found.</p>
        <?php endif; ?>
    </section>

    <footer>
        <!-- Your footer content here -->
    </footer>
</body>
</html>
