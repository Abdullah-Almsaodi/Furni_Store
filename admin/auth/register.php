<?php
session_start();
require_once '../pages/config.php';
require_once '../classes/Database.php';
require_once '../classes/Repository/UserRepository.php'; // Include UserRepository
require_once '../classes/Manager/UserManager.php'; // Include UserManager

$dbInstance = Database::getInstance();
$db = $dbInstance->getInstance()->getConnection();

$userRepository = new UserRepository($db); // Create an instance of UserRepository
$userManager = new UserManager($userRepository); // Create an instance of UserManager

// Define variables and set to empty values
$username = $email = $password = $password1 = "";
$errors = array();

if (isset($_POST['register'])) {



    $username = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password1 = $_POST['password1'] ?? '';


    // Use UserManager to register the user
    $registerResult = $userManager->addUser($username, $email, $password, $password1, 2); // Assuming role_id 2 for user

    if ($registerResult['success']) {
        // Redirect the user to the login page with a success message
        $_SESSION['message'] = 'User registration successful. Please log in.';


        header('Location: login.php');
        exit;
    } else {

        $errors = $registerResult['errors'];
        // $errors = ['email' => 'not found'];
    }
}


// You may want to display errors here if any exist




?>









<!-- Registration form HTML here -->
<?php
// include_once '../include/Header.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>Furin</title>

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Yem-Yem Supermarket">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="author" content="Yem-Yem">
    <meta name="generator" content="Yem-Yem Supermarket">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../../Public/images/favicon.png" />

    <link rel="stylesheet" href="../../plugins/themefisher-font/style.css">
    <!-- bootstrap.min css -->
    <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">

    <!-- Animate css -->
    <link rel="stylesheet" href="../../plugins/animate/animate.css">
    <!-- Slick Carousel -->
    <link rel="stylesheet" href="../../plugins/slick/slick.css">
    <link rel="stylesheet" href="../../plugins/slick/slick-theme.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="../../Public/css/style1.css">

</head>

<body id="body">

    <section class="signin-page account">
        <div class="container">
            <div class="row">

                <div class="col-md-6 col-md-offset-3">
                    <div class="block text-center">
                        <a href="/">
                            <svg width="250px" height="29px" viewBox="0 0 200 29" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                    font-size="40" font-family="AustinBold, Austin" font-weight="bold">
                                    <g id="Group" transform="translate(-108.000000, -297.000000)" fill="#000000">
                                        <text id="AVIATO">
                                            <tspan x="150.94" y="325">Furin</tspan>
                                        </text>
                                    </g>
                                </g>
                            </svg>
                        </a>
                        <form class="text-left clearfix" method="POST"
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" value="<?php echo $username; ?>"
                                    placeholder=" Enter your name">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['username'])) echo  $errors['username']
                                    ?>
                                </span>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>"
                                    placeholder=" Enter your email">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['email'])) echo  $errors['email'];
                                    // elseif (isset($errors['emailEM'])) {
                                    //     echo  $errors['emailEM'];                          
                                    // }
                                    ?>
                                </span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="
                                    form-control" placeholder="Enter your password">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['password'])) echo  $errors['password'];

                                    ?>
                                </span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password1" class="form-control"
                                    placeholder="Confirm your password">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['password'])) echo  $errors['password'];
                                    elseif (isset($errors['password1'])) {
                                        echo  $errors['password1'];
                                    }
                                    ?>
                                </span>

                            </div>
                            <div class="text-center">
                                <button name="register" type="submit" class="btn btn-main text-center">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <?php

    // include "../include/Footer.php";

    ?>

    <!-- 
    Essential Scripts
    =====================================-->

    <!-- Main jQuery -->
    <script src="../../plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.1 -->
    <script src="../../plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap Touchpin -->
    <script src="../../plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
    <!-- Slick Carousel -->
    <script src="../../plugins/slick/slick.min.js"></script>
    <!-- Counterup -->
    <script src="../../plugins/counterup/jquery.counterup.min.js"></script>
    <!-- Waypoints -->
    <script src="../../plugins/waypoints/jquery.waypoints.min.js"></script>
    <!-- Wow JS -->
    <script src="../../plugins/wow/wow.min.js"></script>
    <!-- Main Script -->
    <script src="../../js/script.js"></script>

</body>

</html>