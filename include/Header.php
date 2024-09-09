<!-- <?php
        // Check if a session is already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is registered
        $isRegistered = false; // Replace with your logic to determine if the user is registered

        // Example: Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            $isRegistered = true;
        }

        ?> -->


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="../Public/images/favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Render all elements normally  -->
    <link href="css/nrmalize.css" rel="stylesheet">

    <!-- Font Awesome Library -->
    <link href="plugins/fontawesome/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="../Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Public/css/tiny-slider.css" rel="stylesheet">
    <link href="../Public/css/style.css" rel="stylesheet">
    <title>Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co </title>


    <!-- <style>
        a {
            font-size: 17px;
        }
    </style> -->
</head>

<body>

    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" id="nav"
        arial-label="Furni navigation bar">

        <div class="container">
            <a class="navbar-brand" href="index.php">Furni </a>
            <a href="index.php"><img src="images/favicon.png" alt="icon images"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
                aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsFurni">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li><a class="nav-link" href="products.php">Products</a></li>
                    <li><a class="nav-link" href="about.php">About us</a></li>
                    <li><a class="nav-link" href="services.php">Services</a></li>
                    <li><a class="nav-link" href="blog.php">Blog</a></li>
                    <!--    <li><a class="nav-link" href="test.php">test</a></li> -->
                    <li><a class="nav-link" href="contact.php">Contact us</a></li>
                    <li><a class="nav-link" href="cart.php"><img src="../Public/images/cart.svg"></a></li>
                </ul>

                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <?php
                    // Check if the user is registered

                    if ($isRegistered) {
                        // User is registered, display profile link
                        echo '<li><a class="nav-link" href="profile.php"><img src="../Public/images/user.svg"></a></li>';
                        echo '<li><a class="nav-link" href="admin/logout.php"><i class="fas fa-sign-out-alt"></i></a></li>';
                    } else {
                        // User is not registered, display login link
                        echo '<li><a class="nav-link" href="login.php">Login</a></li>';
                        echo '<li><a class="nav-link" href="register.php">Register</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

    </nav>
    <!-- End Header/Navigation -->