<?php
session_start();
include 'db_connect.php';

// define variables and set to empty values
$name = $email = $password = $password1 = "";
$errors = array();


if ($_SERVER["REQUEST_METHOD"] == "POST") {



    if (empty($_POST["name"])) {
        $errors['nameE'] = " Name is required";
    } else {
        $name = test_input($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $errors['nameE'] = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["email"])) {
        $errors['emailE'] = " Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailE'] = "Invalid email format";
        }
    }

    if (empty($_POST["password"] || $_POST["password1"])) {
        $errors['passE'] =  " Password is required";
    } else {

        $password = test_input($_POST["password"]);
        $password1 = test_input($_POST["password1"]);

        // check if Passwords do  match
        if ($_POST["password"] != $_POST["password1"]) {
            $errors['passEM'] =  "Passwords do not match.";
        }
    }


    if (empty($errors)) {


        // start prepare after all checks
        $role = 2;
        $query = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $errors['emailE'] =  "A user with that email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $activation_token = bin2hex(random_bytes(16));

            $query = "INSERT INTO Users (name, email,role_id, password, activation_token) VALUES (:name, :email, :role_type, :password, :activation_token)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role_type', $role);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':activation_token', $activation_token);

            if ($stmt->execute()) {


                // Redirect the user to the login page with a success message
                $_SESSION['message'] = 'User registration successful. Please log in.';
                header('Location: login.php');
                exit;
            } else {
                $errorInfo = $db->errorInfo();
                echo "Error: " . $errorInfo[2];
            }
        }
    }
}


// Function to retrieve role_id by type
function getRoleIdByType($db, $type)
{
    $query = "SELECT role_id FROM Roles WHERE type = :type";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':type', $type);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        return $result['role_id'];
    }

    return null;
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}





?>









<!-- Registration form HTML here -->
<?php
include_once 'include\Header.php';
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
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />

    <link rel="stylesheet" href="plugins/themefisher-font/style.css">
    <!-- bootstrap.min css -->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">

    <!-- Animate css -->
    <link rel="stylesheet" href="plugins/animate/animate.css">
    <!-- Slick Carousel -->
    <link rel="stylesheet" href="plugins/slick/slick.css">
    <link rel="stylesheet" href="plugins/slick/slick-theme.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style1.css">

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
                                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>"
                                    placeholder=" Enter your name">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['nameE'])) echo  $errors['nameE']
                                    ?>
                                </span>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>"
                                    placeholder=" Enter your email">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['emailE'])) echo  $errors['emailE'];
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
                                    if (isset($errors['passE'])) echo  $errors['passE'];

                                    ?>
                                </span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password1" class="form-control"
                                    placeholder="Confirm your password">
                                <span style="color:red">
                                    <?php
                                    if (isset($errors['passE'])) echo  $errors['passE'];
                                    elseif (isset($errors['passEM'])) {
                                        echo  $errors['passEM'];
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

    include "include\Footer.php";

    ?>

    <!-- 
    Essential Scripts
    =====================================-->

    <!-- Main jQuery -->
    <script src="plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.1 -->
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap Touchpin -->
    <script src="plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
    <!-- Slick Carousel -->
    <script src="plugins/slick/slick.min.js"></script>
    <!-- Counterup -->
    <script src="plugins/counterup/jquery.counterup.min.js"></script>
    <!-- Waypoints -->
    <script src="plugins/waypoints/jquery.waypoints.min.js"></script>
    <!-- Wow JS -->
    <script src="plugins/wow/wow.min.js"></script>
    <!-- Main Script -->
    <script src="js/script.js"></script>

</body>

</html>