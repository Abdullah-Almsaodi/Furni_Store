<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: ../login.php");
    exit;
}

// Check if the user has the necessary role or permission
if ($_SESSION['role'] !== 'Admin') {
    // Redirect to an unauthorized page or show an error message
    header("Location: ../unauthorized.php");
    exit;
}

// If the user is authenticated and has the admin role, proceed with displaying the admin dashboard

// Include your necessary files and functions
// require_once('../functions.php');

// Retrieve and display the necessary data and functionality for the admin dashboard





// Other admin dashboard content goes here

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Free Bootstrap Admin Template : Binary Admin</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Dashboard</a>
            </div>
            <div style="color: white;
padding: 15px 50px 5px 50px;
float: right;
font-size: 16px;">

                <!-- Example: Display the logged-in user's information "! This is the admin dashboard." -->
                <span><?php echo "Welcome, " . $_SESSION['username']; ?></span>
                <a href="logout.php" class="btn btn-success square-btn-adjust">Logout</a>
                <a href="../index.php" class="btn btn-success square-btn-adjust">Home</a>
            </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="users.php"><i class="fa fa-users "></i>Users</a>
                    </li>
                    <li>
                        <a href="categories.php"><i class="fa fa-tasks"></i>Categories </a>
                    </li>
                    <li>
                        <a href="products.php"><i class="fa fa-bars"></i>Products</a>
                    </li>
                    <li>
                        <a href="database.php"><i class="fa fa-bars"></i>Database</a>
                    </li>



                </ul>

            </div>

        </nav>