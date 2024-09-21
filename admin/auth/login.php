<?php
require_once '../pages/config.php';
require_once '../classes/Database.php';
require_once '../classes/Manager/UserManager.php'; // Include UserManager
require_once '../classes/Repository/UserRepository.php'; // Include UserRepository

// Start the session
session_start();

$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();

$userRepository = new UserRepository($conn); // Create an instance of UserRepository
$userManager = new UserManager($userRepository); // Create an instance of UserManager

// Login PHP script
$email = $password = "";
$errors = array();

if (isset($_POST['login'])) {
    if (empty($_POST["email"])) {
        $errors['email'] = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // Check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $errors['password'] = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($errors)) {


        // Use UserManager to login the user
        $loginResult = $userManager->loginUser($email, $password);

        // $testPassword = '$2y$10$flWsRcaATPyg6Nq5MHm7kO.KVAbzSCNtEqjBcTSQ1x42VEd.NqX8a'; // Replace with a known password
        // $storedHash = $loginResult['password']; // Retrieve the hashed password from the user record




        if ($loginResult['success']) {
            // Successful login
            $_SESSION['user_id'] = $loginResult['user']['id'];
            $_SESSION['username'] = $loginResult['user']['username'];
            $_SESSION['useremail'] = $loginResult['user']['email'];
            $_SESSION['role'] = $loginResult['user']['role_name']; // Make sure to include this in your user data

            // Redirect based on role
            if ($_SESSION['role'] == "Admin") {
                header("Location: ../pages/index.php");
            } else {
                header("Location: ../../Public/index.php");
            }
            exit();
        } else {





            // Handle errors
            $errors = array_merge($errors, $loginResult['errors']);
        }
    }
}

// You may want to display errors here if any exist







function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


<?php
// include_once '../../include/Header.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title> Furin Login </title>

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">


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

    <style>
        #bout {
            background-color: #3b5d50;
            border: none;
            border-radius: 6px;
            font-size: 20px;
            font-weight: bold;
            height: 50px;
            width: 100px;
            padding: 0;
            margin: 10px;
        }

        #bout:hover {

            background-color: black;
            color: white;
        }

        #cont {
            box-shadow: 0px 0 30px rgba(5, 1, 5, 0.3);
            border-radius: 6px;

        }

        #h2 {
            font-size: 35px;
        }
    </style>

</head>

<body id="body">

    <section class="signin-page account">
        <div class="container">

            <div class="user">


                <div class="col-md-8"></div>
                <div class="col-md-6 col-md-offset-3">
                    <div id="cont" class="block text-center">
                        <a href="/">
                            <svg width="250px" height="29px" viewBox="0 0 200 29" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                    font-size="40" font-family="AustinBold, Austin" font-weight="bold">
                                    <g id="Group" transform="translate(-108.000000, -297.000000)" fill="#000000">
                                        <text id="AVIATO">
                                            <tspan x="150.94" y="325"> Furin </tspan>
                                        </text>
                                    </g>
                                </g>
                            </svg>
                            <h2 id="h2" class="text-center"> Welcome </h2>
                            <?php
                            if (isset($_SESSION['message'])) {
                                echo '<p style="color: green;">' . $_SESSION['message'] . '</p>';

                                unset($_SESSION['message']);
                            }


                            if (isset($errors['active'])) {
                                echo '<p style="color: green;">' . $errors['active'] . '</p>';
                            }
                            ?>
                        </a>
                        <form class="text-left clearfix" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
                                                                                ?>">
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Email">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['email'])) echo  $errors['email'];

                                    ?>
                                </span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['password'])) echo  $errors['password'];
                                    ?>
                                </span>
                            </div>

                            <div class="text-center">
                                <button id="bout" name=" login" type="submit"
                                    class="btn btn-main text-center">Login</button>
                            </div>
                        </form>
                        <p class="mt-20">Don't have an account ?<a href="Register.php"> Create New Account</a></p>
                        <!-- <p class="mt-20"><a href="restpassword.php">Forgot Password?</a></p> -->
                    </div>
                </div>




            </div>
        </div>
    </section>

    <?php
    // require_once('../include/Footer.php');
    //require('dbconnect.php');
    ?>

    <!-- 
    Essential Scripts
    =====================================-->

    <!-- Main jQuery -->
    <script src=" plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.1 -->
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap Touchpin -->
    <script src="plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js">
    </script>
    <!-- Video Lightbox Plugin -->
    <script src="plugins/ekko-lightbox/dist/ekko-lightbox.min.js"></script>
    <!-- Count Down Js -->
    <script src="plugins/syo-timer/build/jquery.syotimer.min.js"></script>

    <!-- slick Carousel -->
    <script src="plugins/slick/slick.min.js"></script>
    <scriptApologies for the abrupt end of the response. Here's the continuation of the converted front-end code:
        ```html src="plugins/slick/slick-animation.min.js">
        </script>

        <!-- Main Js File -->
        <script src="js/script.js"></script>
</body>

</html>