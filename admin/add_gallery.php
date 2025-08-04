<?php
// Include the database connection file
include '../connection.php';

// Initialize variables
$location_name = '';
$image_urls = [];  // Array to store image URLs
$message = '';  // Variable to store success or error message

// Define allowed image file types
$allowed_image_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sanitize and validate inputs
    if (isset($_POST['location_name']) && !empty($_POST['location_name'])) {
        $location_name = htmlspecialchars(trim($_POST['location_name']));
    } else {
        $message = "<div class='error'>Location Name is required.</div>";
    }

    // Handle file uploads
    $uploads_dir = 'uploads'; // General folder for storing gallery images

    // Make sure the uploads directory exists, and is writable
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);  // Create the directory if it doesn't exist
    }

    // Function to handle image upload
    function handle_image_upload($file, $uploads_dir, $allowed_image_types) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $image_name = basename($file['name']);
            $image_tmp = $file['tmp_name'];
            $image_type = mime_content_type($image_tmp);
            
            // Check if the file is a valid image type
            if (in_array($image_type, $allowed_image_types)) {
                // Generate a unique name for the image to avoid overwriting
                $image_unique_name = uniqid('img_', true) . '.' . pathinfo($image_name, PATHINFO_EXTENSION);
                $image_path = $uploads_dir . '/' . $image_unique_name;

                // Move the uploaded file to the uploads folder
                if (move_uploaded_file($image_tmp, $image_path)) {
                    return $image_unique_name; // Return the new image name
                } else {
                    return false; // Error uploading the image
                }
            } else {
                return false; // Invalid image type
            }
        } else {
            return false; // Error in file upload
        }
    }

    // Handle dynamic image uploads (images 1, 2, 3, ...)
    $total_images = 3; // Maximum number of images allowed
    for ($i = 1; $i <= $total_images; $i++) {
        if (isset($_FILES['image' . $i]) && $_FILES['image' . $i]['error'] === UPLOAD_ERR_OK) {
            $uploaded_image = handle_image_upload($_FILES['image' . $i], $uploads_dir, $allowed_image_types);
            if ($uploaded_image) {
                $image_urls[] = $uploaded_image; // Add image to the array
            } else {
                // Debugging: Add error message for failed upload
                $message = "<div class='error'>Error uploading image " . $i . ".</div>";
                break; // Exit the loop if any image fails
            }
        } else {
            // Debugging: Handle file upload errors (e.g., no file uploaded)
            if ($_FILES['image' . $i]['error'] != UPLOAD_ERR_NO_FILE) {
                $message = "<div class='error'>Error with image " . $i . ": " . $_FILES['image' . $i]['error'] . "</div>";
                break; // Exit the loop on error
            }
        }
    }

    // Check if location name is provided and at least one image is uploaded
    if ($location_name && count($image_urls) > 0) {
        try {
            // Convert the image URLs array into a comma-separated string
            $image_urls_string = implode(",", $image_urls);

            // Prepare SQL query to insert gallery data
            $query = "INSERT INTO gallery (location_name, media_url, media_type, created_at) 
                      VALUES (:location_name, :media_url, :media_type, NOW())";

            // Prepare statement
            $stmt = $pdo->prepare($query);

            // Media type for all images
            $media_type = 'image';

            // Execute the insert query
            $stmt->execute([
                ':location_name' => $location_name,
                ':media_url' => $image_urls_string,  // All image URLs as a comma-separated string
                ':media_type' => $media_type
            ]);

            $message = "<div class='success'>Gallery images added successfully!</div>";

        } catch (PDOException $e) {
            $message = "<div class='error'>Error: " . $e->getMessage() . "</div>";
        }
    } else {
        $message = "<div class='error'>Please fill all fields and upload at least one image.</div>";
    }
}
?>

<!-- Include the Navbar -->
<?php include 'navbar.php'; ?>

<!-- Main Content Area -->
<div class="main-content">
    <div class="form-container">
        <h1>Add New Gallery</h1>

        <!-- Gallery Form -->
        <form method="POST" action="add_gallery.php" enctype="multipart/form-data">
            <!-- Location Name Input -->
            <label for="location_name">Location Name:</label>
            <input type="text" name="location_name" id="location_name" placeholder="Enter Location Name" required><br>

            <!-- Dynamic Image Upload -->
            <?php for ($i = 1; $i <= 3; $i++) { ?>
                <label for="image<?php echo $i; ?>">Image <?php echo $i; ?>:</label>
                <input type="file" name="image<?php echo $i; ?>"><br>
            <?php } ?>

            <!-- Submit Button -->
            <input type="submit" value="Add Gallery">
        </form>

        <!-- Display success or error messages below the form -->
        <?php if (!empty($message)) { ?>
            <div class="message-container">
                <?php echo $message; ?>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Form Styling -->
<style>
    /* Form Container Styling */
    .main-content {
        margin-left: 110px;
        padding: 20px;
        width: calc(100% - 250px);
    }
    .form-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    /* Heading Styling */
    .form-container h1 {
        font-size: 24px;
        color: #2C3E50;
        margin-bottom: 20px;
        font-weight: 600;
    }

    /* Label Styling */
    .form-container label {
        font-size: 1.1rem;
        margin-bottom: 10px;
        display: block;
        text-align: left;
        color: #34495E;
    }

    /* Input Fields Styling */
    .form-container input[type="text"],
    .form-container input[type="file"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0 20px;
        border-radius: 4px;
        border: 1px solid #BDC3C7;
        font-size: 1rem;
    }

    /* Submit Button Styling */
    .form-container input[type="submit"] {
        background-color: #3498db;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    /* Submit Button Hover Effect */
    .form-container input[type="submit"]:hover {
        background-color: #2980b9;
    }

    /* Success and Error Messages Styling */
    .message-container {
        width: 100%;
        margin-top: 20px;
    }

    .success {
        background-color: #2ecc71;
        color: white;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        font-size: 1rem;
    }

    .error {
        background-color: #e74c3c;
        color: white;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        font-size: 1rem;
    }
</style>

</body>
</html>
