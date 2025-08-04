<?php
// Include the database connection
include '../connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from form
    $destination_name = $_POST['destination_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define the upload directory and move the uploaded file
        $upload_dir = 'uploads/';
        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Insert data into the database
            $insert_query = "INSERT INTO packages1 (destination_name, description, price, image_url) 
                             VALUES (:destination_name, :description, :price, :image_url)";
            $stmt = $pdo->prepare($insert_query);
            $stmt->execute([
                ':destination_name' => $destination_name,
                ':description' => $description,
                ':price' => $price,
                ':image_url' => $image_name
            ]);

            echo "<script>alert('Package added successfully!');</script>";
        } else {
            echo "<script>alert('Failed to upload the image.');</script>";
        }
    } else {
        echo "<script>alert('Please select an image to upload.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Package - Admin</title>
    <link rel="stylesheet" href="admin.css"> <!-- You can use your own admin CSS -->
    <style>
        /* Admin Container */
.admin-container {
    /* margin-left: 250px; Offset for the fixed sidebar */
    padding: 30px;
    background-color: #f9f9f9;
    min-height: 100vh;
}

/* Heading Styling */
.admin-container h1 {
    font-size: 28px;
    color: #003366;
    margin-bottom: 30px;
    font-weight: 600;
    text-align: center;
}

/* Form Styling */
form {
    background-color: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 0 auto; /* Centering the form */
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
}

label {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    display: block;
    margin-bottom: 8px;
}

input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 12px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    background-color: #fafafa;
}

input[type="file"] {
    padding: 5px;
}

textarea {
    resize: vertical;
}

/* Button Styling */
button[type="submit"] {
    background-color: #003366;
    color: white;
    font-size: 16px;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

button[type="submit"]:hover {
    background-color: #0055cc;
}

/* Optional: Add Focus Styling */
input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus,
input[type="file"]:focus {
    outline: none;
    border-color: #0055cc;
}

/* Responsive design for smaller screens */
@media screen and (max-width: 768px) {
    .admin-container {
        padding: 20px;
    }

    form {
        padding: 15px;
    }

    button[type="submit"] {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <!-- Include the Navbar -->
<?php include 'navbar.php'; ?>
    <div class="admin-container">
        <h1>Add New Package</h1>
        <form action="add_packages.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="destination_name">Destination Name:</label>
                <input type="text" id="destination_name" name="destination_name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="image">Package Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <button type="submit">Add Package</button>
        </form>
    </div>
</body>
</html>
