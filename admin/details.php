<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tour";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate uploaded files
function validateFile($file, $allowedTypes, $maxSize) {
    $fileType = mime_content_type($file['tmp_name']);
    $fileSize = $file['size'];

    // Check file type
    if (!in_array($fileType, $allowedTypes)) {
        return "Invalid file type. Allowed types: " . implode(", ", $allowedTypes);
    }

    // Check file size
    if ($fileSize > $maxSize) {
        return "File size exceeds the limit of " . ($maxSize / 1024 / 1024) . " MB.";
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs and sanitize them
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $attractions = htmlspecialchars(trim($_POST['attractions']));
    $special_attractions = htmlspecialchars(trim($_POST['special-attractions']));
    $extra_info = htmlspecialchars(trim($_POST['extra-info']));
    $package_name = htmlspecialchars(trim($_POST['package-name']));
    $duration = htmlspecialchars(trim($_POST['duration']));
    $price = htmlspecialchars(trim($_POST['price']));
    $includes = htmlspecialchars(trim($_POST['includes']));

    // Validation for form inputs
    if (empty($name) || strlen($name) > 255) {
        die("Invalid destination name. Please ensure it is not empty and is under 255 characters.");
    }
    if (empty($description) || strlen($description) > 1000) {
        die("Invalid description. Please ensure it is not empty and is under 1000 characters.");
    }
    if (!is_numeric($price) || $price <= 0) {
        die("Invalid price. Please provide a positive numeric value.");
    }

    // Validate duration (accepts format 'X Days / Y Nights')
    if (empty($duration) || !preg_match("/^\d+\sDays\s\/\s\d+\sNights$/", $duration)) {
        die("Invalid duration. Please provide a valid duration in the format 'X Days / Y Nights'.");
    }

    // File upload paths
    $image_target_dir = "uploads/"; // Ensure the trailing slash

    // Ensure the correct directory exists and is writable
    if (!is_dir($image_target_dir)) {
        mkdir($image_target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    if (!is_writable($image_target_dir)) {
        die("Upload directory is not writable.");
    }

    // Allowed file types and size limit (2MB)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    // Check for file upload errors
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("File upload error: " . $_FILES['image']['error']);
    }

    // Validate and move uploaded files
    $image_validation = validateFile($_FILES['image'], $allowedTypes, $maxSize);

    if ($image_validation === true) {
        // Generate unique file name for the image
        $image_file_name = uniqid('image_') . '_' . basename($_FILES['image']['name']);
        $image_target_file = $image_target_dir . $image_file_name; // Full path to the uploaded file

        // Move the file to the correct directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_target_file)) {
            // Save the relative path to the image in the database
            $image_url = "uploads/" . $image_file_name; // Save the relative URL
            $stmt = $conn->prepare("INSERT INTO destinations (name, description, attractions, special_attractions, extra_info, package_name, duration, price, includes, image_url, created_at, updated_at)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("ssssssssss", $name, $description, $attractions, $special_attractions, $extra_info, $package_name, $duration, $price, $includes, $image_url);

            if ($stmt->execute()) {
                $success_message = "Destination added successfully!";
            } else {
                error_log("Database Error: " . $stmt->error, 3, "/path/to/error.log");
                die("An error occurred while adding the destination. Please try again later.");
            }

            $stmt->close();
        } else {
            die("Error uploading the image. Please try again.");
        }
    } else {
        die($image_validation);
    }
}

// Fetch all destinations from the database to display
$sql = "SELECT id, name, description, attractions, special_attractions, extra_info, package_name, duration, price, includes, image_url FROM destinations ORDER BY created_at DESC";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Destination</title>
    <link rel="stylesheet" href="../style/admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

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
            text-align: center;
        }

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
            border-radius: 6px;
        }

        ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="admin-panel">
        <main class="main-content">
            <section id="add_destination" class="content-section">
                <h2>Add New Destination</h2>

                <?php if (isset($success_message)) { ?>
                    <div class="success-message"><?php echo $success_message; ?></div>
                <?php } ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="name">Destination Name</label>
                    <input type="text" id="name" name="name" required>

                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required></textarea>

                    <label for="attractions">Attractions</label>
                    <input type="text" id="attractions" name="attractions" required>

                    <label for="special-attractions">Special Attractions</label>
                    <input type="text" id="special-attractions" name="special-attractions">

                    <label for="extra-info">Extra Information</label>
                    <textarea id="extra-info" name="extra-info" rows="4"></textarea>

                    <label for="package-name">Package Name</label>
                    <input type="text" id="package-name" name="package-name">

                    <label for="duration">Duration</label>
                    <input type="text" id="duration" name="duration" required>

                    <label for="price">Price</label>
                    <input type="text" id="price" name="price" required>

                    <label for="includes">Includes</label>
                    <textarea id="includes" name="includes" rows="4"></textarea>

                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" accept="image/*" required>

                    <button type="submit">Add Destination</button>
                </form>
            </section>

            <section id="manage_destinations" class="content-section">
                <h2>Manage Destinations</h2>
                <?php if ($result->num_rows > 0) { ?>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Attractions</th>
                            <th>Image</th>
                        </tr>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td>
                                    <ul>
                                        <?php
                                        // Split the attractions string by commas and display them as a list
                                        $attractions_list = explode(',', $row['attractions']);
                                        foreach ($attractions_list as $attraction) {
                                            echo "<li>" . htmlspecialchars(trim($attraction)) . "</li>";
                                        }
                                        ?>
                                    </ul>
                                </td>
                                <td><img src="<?php echo $row['image_url']; ?>" width="100" alt="Destination Image"></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } else { ?>
                    <p>No destinations found. Please add a new destination.</p>
                <?php } ?>
            </section>
        </main>
    </div>
</body>
</html>
