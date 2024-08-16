<?php
// admin/classes/UserManager.php 
class UsertManager
{
    private $conn;
    // private $name;
    // private $email;
    // private $password;
    // private $password1;




    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

    // function for adding users 
    public function addUser($name, $email, $password, $password1, $role_type)
    {
        // start prepare after all checks
        $query = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $errors['emailE'] =  "A user with that email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $activation_token = bin2hex(random_bytes(16));

            $query = "INSERT INTO users (name, email, password, activation_token) VALUES (:name, :email, :password, :activation_token)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':activation_token', $activation_token);

            if ($stmt->execute()) {

                // Get the last inserted user ID
                $userId = $this->conn->lastInsertId();

                // Insert the role into the "role" table
                $roleQuery = "INSERT INTO Roles (type, role_id) VALUES (:type, :role_id)";
                $roleStmt = $this->conn->prepare($roleQuery);
                $roleStmt->bindParam(':type', $role_type);
                $roleStmt->bindParam(':role_id', $userId);

                if ($roleStmt->execute()) {
                    echo '<script type="text/javascript">
                    $(document).ready(function() {
                        $(function() {
                            // Show Bootstrap modal
                            $("#successModal").modal("show");
                        });
                    });
                    </script>';
                } else {
                    $errorInfo = $this->conn->errorInfo();
                    echo "Error: " . $errorInfo[2];
                }
            } else {
                $errorInfo = $this->conn->errorInfo();
                echo "Error: " . $errorInfo[2];
            }
        }

        // Logic to add user
    }

    public function getUsers()
    {
        // Logic to fetch users
    }

    public function deleteUser($id)
    {
        // Logic to delete user
    }

    // More methods as needed
}