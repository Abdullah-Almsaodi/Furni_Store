<?php
// Start the session
session_start();

// Include the database connect file
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id']; // Retrieve the user ID from the session

// Retrieve the user's information from the database
$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Handle the case if the user's information is not found
    header("Location: error.php"); // Redirect to an error page
    exit;
}

$name = $user['name'];
$email = $user['email'];
$phone = $user['phone_number'];
$address = $user['address'];
$aboutme = $user['aboutme'];
$birthdate = $user['brithdata'];
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

    if (empty($_POST["phone"])) {
        $errors['phoneE'] = "Phone Number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        // check if phone only contains numbers
        if (!preg_match("/^[0-9]*$/", $phone)) {
            $errors['phoneE'] = "Only numeric values allowed";
        }
    }

    if (empty($_POST["address"])) {
        $errors['addressE'] = "Address is required";
    } else {
        $address = test_input($_POST["address"]);
        // check if address is valid
        if (!preg_match("/^[a-zA-Z0-9\s,'-]*$/", $address)) {
            $errors['addressE'] = "Invalid address format";
        }
    }

    if (empty($_POST["aboutme"])) {
        $errors['aboutmeE'] = "About me is required";
    } else {
        $aboutme = test_input($_POST["aboutme"]);
        // check if about me only contains letters, whitespace, and numbers
        if (!preg_match("/^[a-zA-Z0-9\s,'-]*$/", $aboutme)) {
            $errors['aboutmeE'] = "Only letters, numbers, whitespace, commas, apostrophes, and hyphens allowed in the about me field";
        }
    }

    if (empty($_POST["birthdate"])) {
        $errors['birthdateE'] = "Birthdate is required";
    } else {
        $birthdate = test_input($_POST["birthdate"]);
        // Additional validation for the birthdate if needed
    }

    // Validate image
    if (!empty($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");

        // Check if the uploaded file is a valid image file
        if (!in_array($imageFileType, $allowedExtensions)) {
            $errors['imageE'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            // Check for upload errors
            $uploadErrors = array(
                UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
            );

            $errorCode = $_FILES['image']['error'];
            if (isset($uploadErrors[$errorCode])) {
                $errors['imageE'] = $uploadErrors[$errorCode];
            } else {
                $errors['imageE'] = 'Unknown error occurred during file upload.';
            }
        }
    } else {
        $errors['imageE'] = "Image is required";
    }

    if (empty($errors)) {
        // Check if the email already exists in the "users" table
        $query = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();


        // Upload the image file to the server
        $targetDirectory = "uploads/user_img/";
        $targetFile = $targetDirectory . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

        // Updata the user data into the "users" table
        $query = "UPDATE Users SET name = :name, email = :email, phone_number = :phone, address = :address, aboutme = :aboutme, brithdata = :birthdate, image = :image WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->execute(array(
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':aboutme' => $aboutme,
            ':birthdate' => $birthdate,
            ':image' => $_FILES['image']['name'],
            ':user_id' => $user_id
        ));

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            // Redirect the user to the login page with a success message
            // Set a session variable to indicate successful user update
            $_SESSION['user_updated'] = true;
            $_SESSION['message'] = 'User Update successful.';
            header("Location: profile.php?success=1");
            exit;
        } else {
            // Display an error message or handle the error as needed
            $errorInfo = $db->errorInfo();
            echo "Error: " . $errorInfo[2];
        }
    }
}



function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<?php

// Include the Header file
include 'include/Header.php';
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>User Profile Page</title>
    <style>
    /* Custom styles for the profile page */
    body {
        background-color: #f8f9fa;
    }


    .profile-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .profile-picture {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        object-position: center;
        border: 5px solid #007bff;
        cursor: pointer;
        /* Add cursor pointer for clickable behavior */
    }

    .custom-navbar,
    button {
        background: #007bff !important;

    }

    .profile-heading {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-heading h2 {
        font-size: 28px;
        font-weight: bold;
        color: black;
        margin-bottom: 10px;
    }

    .profile-heading h1 {
        font-size: 28px;
        font-weight: bold;
        color: black;
        margin: 35px;
    }

    .profile-heading p {
        font-size: 18px;
        color: #777;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: bold;
        color: #333;
    }

    .form-control {
        border-radius: 5px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        font-weight: bold;
    }

    .error {
        color: red;
    }

    .success-message {
        background-color: #dff0d8;
        color: #3c763d;
        border: 1px solid #d6e9c6;
        padding: 10px;
        margin-bottom: 20px;
    }

    .profile-container button {
        text-align: center;
        margin: 0 40%;
        border: none;
        font-size: 20px;
        border-radius: 10px;
        height: 50px;
    }
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="profile-heading">
            <img src="uploads/user_img/<?php echo $user['image']; ?>" alt="Profile Picture" class="profile-picture"
                id="profilePicture">
            <h1>Welcome, <?php echo $user['name']; ?></h1>

        </div>
        <hr>
        <!-- Add this HTML code where you want to display the success message -->
        <?php if (isset($_SESSION['user_updated']) && $_SESSION['user_updated']) : ?>
        <div class="success-message">
            <p>User update successful.</p>
        </div>
        <?php unset($_SESSION['user_updated']); // Clear the session variable 
            ?>
        <?php endif; ?>
        <!-- <h3><?php print_r($test)  ?></h3> -->
        <form id="profileForm" method="POST" action="profile.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" id="fullName" value="<?php echo $user['name']; ?>">
                <?php if (!empty($errors['nameE'])) : ?>
                <span class="error"><?php echo $errors['nameE']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="<?php echo $user['email']; ?>">
                <?php if (!empty($errors['emailE'])) : ?>
                <span class="error"><?php echo $errors['emailE']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" id="phoneNumber"
                    value="<?php echo $user['phone_number']; ?>">
                <?php if (!empty($errors['phoneE'])) : ?>
                <span class="error"><?php echo $errors['phoneE']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" name="address" id="address"
                    rows="3"><?php echo $user['address']; ?></textarea>
                <?php if (!empty($errors['addressE'])) : ?>
                <span class="error"><?php echo $errors['addressE']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="birthdate" class="form-label">Birthdate</label>
                <input type="date" name="birthdate" class="form-control" id="birthdate"
                    value=" <?php echo $user['brithdata']; ?>">
                <?php if (!empty($errors['birthdateE'])) : ?>
                <span class="error"><?php echo $errors['birthdateE']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="about" class="form-label">About Me</label>
                <textarea class="form-control" name="aboutme" id="about"
                    rows="5"><?php echo $user['aboutme']; ?></textarea>
                <?php if (!empty($errors['aboutmeE'])) : ?>
                <span class="error"><?php echo $errors['aboutmeE']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="photo" class="form-label">Profile Photo</label>
                <input type="file" name="image" class="form-control-file" id="photo" accept="image/*">
                <?php if (!empty($errors['imageE'])) : ?>
                <br>
                <span class="error"><?php echo $errors['imageE']; ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Save </button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    // JavaScript code to handle the profile photo upload
    document.getElementById('photo').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePicture').setAttribute('src', e.target.result);
        };
        reader.readAsDataURL(file);
    });

    // Add click event listener to profile picture
    document.getElementById('profilePicture').addEventListener('click', function() {
        document.getElementById('photo').click(); // Trigger file input click event
    });
    </script>
</body>

</html>