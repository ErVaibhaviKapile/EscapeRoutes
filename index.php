<?php
// Include the database connection file
session_start(); // Start the session to track login status
$is_logged_in = isset($_SESSION['user_id']); // Adjust 'user_id' based on how you store login data
// Test session content
// if (isset($_SESSION['user_id'])) {
//     echo "User is logged in. User ID: " . $_SESSION['user_id'];
// } else {
//     echo "User is not logged in.";
// }


include './connection.php';

// Initialize services and packages arrays
$services = [];
$packages = [];
$galleries = [];  // Array to store gallery data

try {
    // Query to fetch services from the database
    $stmt = $pdo->prepare("SELECT * FROM services ORDER BY created_at DESC");
    $stmt->execute();
    // Fetch all the services from the database
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to fetch packages from the database
    $stmtPackages = $pdo->prepare("SELECT * FROM packages1 ORDER BY created_at DESC");
    $stmtPackages->execute();
    // Fetch all the packages from the database
    $packages = $stmtPackages->fetchAll(PDO::FETCH_ASSOC);

    // Query to fetch gallery data from the database
    $stmtGalleries = $pdo->prepare("SELECT * FROM gallery ORDER BY created_at DESC");
    $stmtGalleries->execute();
    // Fetch all the galleries from the database
    $galleries = $stmtGalleries->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EscapeRoutes</title>
    <link rel="stylesheet" href="home.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <section id="top">
        <nav class="navbar">
            <div class="logo"><span><i class="fa-solid fa-plane-departure"></i>EscapeRoutes</span></div>
            <ul class="nav-links">
                <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#services"><i class="fa fa-concierge-bell" class="services-section"></i> Services</a></li>
                <li><a href="#Packages"><i class="fa fa-box-open"class="container"></i> Packages</a></li>
                <li><a href="#gallery"><i class="fa-regular fa-image"class="gallery"></i>Gallery</a></li>
                <li><a href="contact.php"><i class="fa-regular fa-address-card"></i> Contact Us</a></li>
            </ul>
            <div>
            <?php if ($is_logged_in): ?>
    <!-- Display Logout Button when logged in -->
    <button class="logout-btn" id="logoutBtn" style="background-color: #25a4f9; color: #080606;height:40px; border: none; padding: 8px 8px; font-size: 16px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s; margin-left: 5px;">
    <a href="logout.php" style="text-decoration: none; color: inherit; display: block; height: 100%; width: 100%; padding: 3px 3px;">Logout</a>
</button>

<?php else: ?>
    <!-- Display Login and Register buttons when not logged in -->
    <button class="login-btn" id="loginBtn">Login</button>
    <button class="register-btn" id="registerBtn">Register</button>
<?php endif; ?>




</div>

        </nav>
        </section>
    </header>

    <section class="hero" id="home">
        <div class="hero-video">
            <video autoplay muted loop id="background-video">
                <source src="assets/video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="hero-content">
            <p style="color: rgb(10, 9, 8);"> Explore, Discover, Travel </p><br><br>
            <h1 style="color: black;">"TRAVEL AROUND THE WORLD"</h1>
            
        </div>

 <!-- Login Modal -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeLogin">&times;</span>
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" class="input-field" name="username" placeholder="Username" required>
            <input type="password" class="input-field" name="password" placeholder="Password" required>
            <button type="submit" class="btn-modal">Login</button>
        </form>
    </div>
</div>

<!-- Register Modal -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeRegister">&times;</span>
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" class="input-field" name="full_name" placeholder="Full Name" required>
            <input type="email" class="input-field" name="email" placeholder="Email" required>
            <input type="text" class="input-field" name="username" placeholder="Username" required>
            <input type="password" class="input-field" name="password" placeholder="Password" required>
            <input type="password" class="input-field" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" class="btn-modal">Register</button>
        </form>
    </div>
</div>




    

    </section>
    <!--js for login & register-->
    <script>
       document.addEventListener("DOMContentLoaded", function() {
    const loginModal = document.getElementById("loginModal");
    const registerModal = document.getElementById("registerModal");
    const loginBtn = document.getElementById("loginBtn");
    const registerBtn = document.getElementById("registerBtn");
    const closeLogin = document.getElementById("closeLogin");
    const closeRegister = document.getElementById("closeRegister");

    // Open modals
    loginBtn.onclick = function() { 
        loginModal.style.display = "block"; 
    }
    registerBtn.onclick = function() { 
        registerModal.style.display = "block"; 
    }

    // Close modals
    closeLogin.onclick = function() { 
        loginModal.style.display = "none"; 
    }
    closeRegister.onclick = function() { 
        registerModal.style.display = "none"; 
    }

    // Close modals if clicking outside
    window.onclick = function(event) {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
        if (event.target == registerModal) {
            registerModal.style.display = "none";
        }
    }
});


        // Function to open the booking form modal Countdown Timer
function startCountdown() {
    const countdownElement = document.getElementById('countdown');
    const deadline = new Date("Jan 31, 2025 23:59:59").getTime();

    const interval = setInterval(function() {
        const now = new Date().getTime();
        const remainingTime = deadline - now;

        // Time calculations for days, hours, minutes, and seconds
        const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
        const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

        countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        // If the countdown is over, display a message
        if (remainingTime < 0) {
            clearInterval(interval);
            countdownElement.innerHTML = "Offer Expired!";
        }
    }, 1000);
}

// Start the countdown on page load
window.onload = startCountdown;
    </script>

    <!--js for gallery-->
<script>
    document.querySelectorAll('.slideshow').forEach(slideshow => {
    let currentIndex = 0;
    const slides = slideshow.querySelectorAll('img');

    function changeSlide() {
        // Remove 'active' class from the current image
        slides[currentIndex].classList.remove('active');
        slides[currentIndex].classList.add('inactive');

        // Update index to the next slide
        currentIndex = (currentIndex + 1) % slides.length;

        // Add 'active' class to the next image
        slides[currentIndex].classList.remove('inactive');
        slides[currentIndex].classList.add('active');
    }

    // Initialize the first slide as active
    slides[0].classList.add('active');

    // Change slide every 3 seconds
    setInterval(changeSlide, 3000);
});
</script>


    
<!-- Services Section -->
<section class="services-section" id="services">
    <h1>Our Services</h1>
    <div class="services-container">
        <?php if (!empty($services)) : ?>
            <?php foreach ($services as $service) : ?>
                <div class="service-card">
                    <!-- Display the icon if available -->
                    <?php if (!empty($service['service_icon_url'])) : ?>
                        <img src="<?= htmlspecialchars(ltrim($service['service_icon_url'], './')) ?>" alt="Service Icon" class="service-icon">
                    <?php endif; ?>
                    
                    <!-- Display service name and description -->
                    <h3><?= htmlspecialchars($service['service_name']) ?></h3>
                    <p><?= htmlspecialchars($service['service_description']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No services available at the moment.</p>
        <?php endif; ?>
    </div>
</section>
<style>
    .service-icon {
    width: 50px;  /* Adjust size as needed */
    height: auto; /* Maintain aspect ratio */
    margin-bottom: 10px; /* Space between icon and text */
}

</style>






<!-- Packages Section -->
<section class="container" id="packages">
    <h1>Our Packages</h1>
    <div class="cards">
        <?php if ($packages): ?>
            <?php foreach ($packages as $package): ?>
                <div class="card">
                    <!-- Display the image with the correct relative path -->
                    <img src="admin/uploads/<?= htmlspecialchars($package['image_url']); ?>" alt="<?= htmlspecialchars($package['destination_name']); ?> Image">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($package['destination_name']); ?></h3>
                        <p><?= htmlspecialchars($package['description']); ?></p>
                        <div class="price">$<?= htmlspecialchars($package['price']); ?></div>

                        <!-- Conditionally display the "View More" button or login prompt -->
                        <?php if (isset($_SESSION['username'])): ?>
                            <!-- User is logged in, show the "View More" button -->
                            <a href="destination_details.php?destination_name=<?= urlencode($package['destination_name']); ?>">
                                <button class="view-more-btn">View More</button>
                            </a>
                        <?php else: ?>
                            <!-- User is not logged in, show a message -->
                            <p>You have to <button class="login-btn" id="loginBtn">Login</button> to view more details.</p>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No packages available.</p>
        <?php endif; ?>
    </div>
</section>





    


<!--gallery section-->
<section class="gallery" id="gallery">
    <h1>Image Gallery</h1>
    <div class="box-container">
        <?php if ($galleries): ?>
            <?php foreach ($galleries as $gallery): ?>
                <div class="box">
                    <h3><?php echo htmlspecialchars($gallery['location_name']); ?></h3>
                    <div class="slideshow">
                        <?php 
                            $image_urls = explode(",", $gallery['media_url']); // Convert the comma-separated image URLs into an array
                            foreach ($image_urls as $image_url):
                        ?>
                            <!-- Adjust image path to point to the 'admin/uploads' folder -->
                            <img src="admin/uploads/<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($gallery['location_name']); ?>">
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No galleries available.</p>
        <?php endif; ?>
    </div>
</section>
  
<!--js for gallery-->
<script>
    document.querySelectorAll('.slideshow').forEach(slideshow => {
    let currentIndex = 0;
    const slides = slideshow.querySelectorAll('img');

    function changeSlide() {
        // Remove 'active' class from the current image
        slides[currentIndex].classList.remove('active');
        slides[currentIndex].classList.add('inactive');

        // Update index to the next slide
        currentIndex = (currentIndex + 1) % slides.length;

        // Add 'active' class to the next image
        slides[currentIndex].classList.remove('inactive');
        slides[currentIndex].classList.add('active');
    }

    // Initialize the first slide as active
    slides[0].classList.add('active');

    // Change slide every 3 seconds
    setInterval(changeSlide, 3000);
});
</script>

<!-- Discount Section -->
<section class="discount-section">
    <div class="discount-container">
        <h2>Limited Time Offer: Get 20% Off on All Tours!</h2>
        <p>Book your dream vacation now and save big! Don't miss out on exclusive deals for a limited time only.</p>

        <!-- Optional Countdown Timer -->
        <div class="countdown">
            <p>Hurry! Offer ends in:</p>
            <div id="countdown"></div>
        </div>
    </div>
</section>

<!-- Footer Section -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Why Choose Us?</h3>
            <ul>
                <li>Experienced & Professional Team</li>
                <li>Customized Travel Packages</li>
                <li>24/7 Customer Support</li>
                <li>Exclusive Deals & Discounts</li>
                <li>Trusted by Thousands of Happy Travelers</li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#Packages">Packages</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Contact Us</h3>
            <ul>
                <li>Email: escaperoutes@gmail.com</li>
                <li>Phone: 9876543210</li>
                <li>Address: Nashik, Maharashtra, India</li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Follow Us</h3>
            <ul class="social-links">
                <li><i class="fa-brands fa-facebook"></i></a></li>
                <li><i class="fa-brands fa-linkedin"></i></a></li>
                <li><i class="fa-brands fa-square-instagram"></i></a></li>
                <li><i class="fa-brands fa-youtube"></i></a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 EscapeRouts. All Rights Reserved.</p>
    </div>
</footer>

  </body>
  </html>
