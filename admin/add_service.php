<?php
// Include the database connection file
// Replace these with your actual database credentials
$servername = "localhost";
$username = "root"; // or your database username
$password = ""; // or your database password
$dbname = "tour"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding a new service
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form input values
    $service_name = $_POST['service_name'];
    $service_description = $_POST['service_description'];

    // Handle the SVG file upload
    if (isset($_FILES['service_icon_url']) && $_FILES['service_icon_url']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['service_icon_url']['tmp_name'];
        $file_name = $_FILES['service_icon_url']['name'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        // Only allow SVG files
        if ($file_extension == 'svg') {
            // Set the upload directory and check if it exists
            $upload_dir = '../uploads/icons/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);  // Create the directory if it doesn't exist
            }

            $new_file_name = uniqid('icon_') . '.svg';  // Generate a unique name for the file
            $upload_path = $upload_dir . $new_file_name;

            // Move the uploaded file to the upload directory
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Insert the service details into the database
                $sql = "INSERT INTO services (service_name, service_description, service_icon_url) 
                        VALUES ('$service_name', '$service_description', '$upload_path')";
                if ($conn->query($sql) === TRUE) {
                    echo "New service added successfully!";
                } else {
                    echo "Error: " . $conn->error;
                }
            } else {
                echo "Error uploading the SVG file.";
            }
        } else {
            echo "Only SVG files are allowed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Service</title>
    <link rel="stylesheet" href="../style/admin.css">
    <style>
        /* Existing styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Navbar and container styling */
        .admin-panel {
            margin-top: 20px;
            padding: 20px;
        }

        .content-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.8rem;
            color: #007bff;
            margin-bottom: 20px;
            text-align: center; /* Center the text */
        }

        /* Form Styling */
        form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            font-size: 1rem;
            color: #555;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="file"],
        textarea {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            width: 100%;
        }

        textarea {
            resize: vertical;
        }

        input[type="text"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Table styling for services */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f9;
            color: #333;
        }

        td img {
            max-width: 50px;
            height: auto;
            border-radius: 6px;
        }

        /* Additional Styling */
        .form-container {
            margin-top: 30px;
        }

        .form-container .content-section {
            box-shadow: none;
            padding: 40px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php';?>

<div class="admin-panel">
    <main class="main-content">
        <section id="add_service" class="content-section">
            <h2>Add New Service</h2>
            <form action="add_service.php" method="POST" enctype="multipart/form-data">
                <label for="service_name">Service Name</label>
                <input type="text" id="service_name" name="service_name" required>

                <label for="service_description">Service Description</label>
                <textarea id="service_description" name="service_description" rows="4" required></textarea>

                <label for="service_icon_url">Service Icon (SVG Format)</label>
                <input type="file" id="service_icon_url" name="service_icon_url" accept=".svg" required>

                <button type="submit">Add Service</button>
            </form>
        </section>

        <section id="manage_services" class="content-section">
            <h2>Manage Services</h2>
            <?php 
            // Fetch all services from the database
            $result = $conn->query("SELECT * FROM services");
            if ($result->num_rows > 0) { ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Icon</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['service_id']; ?></td>
                            <td><?php echo $row['service_name']; ?></td>
                            <td><?php echo $row['service_description']; ?></td>
                            <td><img src="<?php echo $row['service_icon_url']; ?>" alt="Service Icon"></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No services found. Please add a new service.</p>
            <?php } ?>
        </section>
    </main>
</div>
</body>
</html>
