<?php
// Start the session
session_start();
include 'db_connect.php';

$email = "";
$previousPage = $_SERVER['HTTP_REFERER'];

if (isset($_POST['reset_password'])) {
    // Retrieve the email address from the form submission
    $email = $_POST['email'];

    // TODO: Validate the email address (e.g., check if it exists in your database)

    // Retrieve the activation token from the database
    $query = "SELECT activation_token FROM users WHERE email = :email";

    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $activationToken = $stmt->fetchColumn();
    $stmt->closeCursor();

    if ($activationToken) {
        // Send the password reset email
        sendResetEmail($email, $activationToken);
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_token'] = $activationToken;
        header("Location: forgetpassword.php");
        exit();
    } else {
        // Handle the case when the email address is not found in the database
        $_SESSION['reset_error'] = "Email address not found.";
        header("Location: forgetpassword.php");
        exit();
    }
}

function sendResetEmail($email, $activationToken)
{
    $resetLink = "https://example.com/reset-password.php?token=" . urlencode($activationToken);

    // Compose the email message
    $subject = "Password Reset";
    $message = "Dear user,\n\n";
    $message .= "To reset your password, please click on the following link:\n";
    $message .= $resetLink . "\n\n";
    $message .= "If you did not request this password reset, please ignore this email.\n";

    // Send the email (you can use a library or your own mailing function)
    mail($email, $subject, $message);
}
?>

<?php
include_once 'include\Header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title> Furin Reset Password </title>

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">


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
            <div class="user">


                <div class="col-md-6 col-md-offset-3">
                    <div class="block text-center">
                        <a href="/">
                            <svg width="250px" height="29px" viewBox="0 0 200 29" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" font-size="40" font-family="AustinBold, Austin" font-weight="bold">
                                    <g id="Group" transform="translate(-108.000000, -297.000000)" fill="#000000">
                                        <text id="AVIATO">
                                            <tspan x="150.94" y="325"> Furin </tspan>
                                        </text>
                                    </g>
                                </g>
                            </svg>
                            <h2 class="text-center"> Welcome </h2>
                            <?php
                            if (isset($_SESSION['message'])) {
                                echo '<p style="color: green;">' . $_SESSION['message'] . '</p>';
                                unset($_SESSION['message']);
                            }
                            if (isset($errors['activeE'])) {
                                echo '<p style="color: green;">' . $errors['activeE'] . '</p>';
                            }
                            ?>
                        </a>
                        <form class="text-left clearfix" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $email; ?>">
                            </div>
                            <button type="submit" class="btn btn-main text-center" name="reset_password">Reset
                                Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once('include\Footer.php');
    //require('dbconnect.php');
    ?>

    <!-- Essential Scripts
  ================================================== -->
    <!-- Main jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 3.1 -->
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Slick Carousel -->
    <script src="plugins/slick/slick.min.js"></script>
    <script src="plugins/slick/slick-animation.min.js"></script>
    <!-- Custom Script -->
    <script src="js/script.js"></script>

</body>

</html>