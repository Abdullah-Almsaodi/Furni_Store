<!-- <?php
    session_start();
    include 'db_connect.php';
    if (isset($_POST['register'])) {

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $password1 = $_POST['password1'];
        $errors = array();

        if (empty($name) || empty($email) || empty($password) || empty($password1)) {
            if (empty($name)) {
                $errors['nameE'] = " Name is required";
            }
            if (empty($email)) {
                $errors['emailE'] = " Email is required";
            }
            if (empty($password || $password1)) {
                $errors['passE'] =  " Password is required";
            }
        } elseif ($password !== $password1) {
            $errors['passEM'] =  "Passwords do not match.";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $errors['nameE'] = " Name should only contain letters"; 
        } else {
            $query = "SELECT user_id FROM Users WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $errors['emailE'] =  "A user with that email already exists.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $activation_token = bin2hex(random_bytes(16));

                $query = "INSERT INTO Users (name, email, password, activation_token) VALUES (:name, :email, :password, :activation_token)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':activation_token', $activation_token);

                if ($stmt->execute()) {
                    // Successful registration
                    // Redirect to the desired page or perform any other actions
                    $message='Users is already logged in';
                    Header('Location:login.php');
                    exit;
                } else {
                    $errorInfo = $pdo->errorInfo();
                    echo "Error: " . $errorInfo[2];
                }
            }
        }
    }
    ?> -->