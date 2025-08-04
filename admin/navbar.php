<!-- navbar.php -->
<nav class="admin-navbar">
    <div class="logo">Tour Admin Panel</div>
    <ul>
        <li><a href="admin.php">Dashboard</a></li>
        <li><a href="add_service.php">Add New Service</a></li>
        <li><a href="add_gallery.php">Add Gallery</a></li>
        <li><a href="add_packages.php">Manage packages</a></li>
        <li><a href="details.php">Packages details</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<style>
    /* Styling for the vertical admin navbar (sidebar) */
    .admin-navbar {
        background-color: #003366;
        color: white;
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .admin-navbar .logo {
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1px;
        margin-bottom: 30px;
        text-align: center;
    }

    .admin-navbar ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .admin-navbar ul li {
        margin: 20px 0;
    }

    .admin-navbar ul li a {
        color: white;
        text-decoration: none;
        font-size: 18px;
        display: block;
        padding: 10px;
        transition: background-color 0.3s;
    }

    .admin-navbar ul li a:hover {
        background-color: #0055cc;
        border-radius: 5px;
    }

    /* Adjust content for the fixed sidebar */
    .main-content {
        /* margin-left: 250px; */
        padding: 20px;
        width: calc(100% - 250px);
    }

    /* Adjust content area for the sidebar */
    body {
        margin-left: 250px; /* Offset content from the sidebar */
        padding-left: 20px;
    }
</style>
