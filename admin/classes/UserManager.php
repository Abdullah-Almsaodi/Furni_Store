<?php
// admin/classes/UserManager.php 

class UserManager
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

    public function isEmailExist($email)
    {
        $query = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function generateActivationToken()
    {
        return bin2hex(random_bytes(16));
    }

    public function addUser($name, $email, $password, $role_type)
    {
        // Validate inputs
        if ($this->isEmailExist($email)) {
            throw new Exception("A user with that email already exists.");
        }

        // Hash password and generate token
        $hashedPassword = $this->hashPassword($password);
        $activationToken = $this->generateActivationToken();

        // Insert user
        $query = "INSERT INTO users (name, email, password, activation_token) 
                  VALUES (:name, :email, :password, :activation_token)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':activation_token', $activationToken);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add user: " . $this->conn->errorInfo()[2]);
        }

        $userId = $this->conn->lastInsertId();

        // Insert role
        $this->addUserRole($role_type, $userId);

        // Show success modal
        $this->showSuccessModal();
    }

    private function addUserRole($role_type, $userId)
    {
        $roleQuery = "INSERT INTO Roles (type, user_id) VALUES (:type, :user_id)";
        $roleStmt = $this->conn->prepare($roleQuery);
        $roleStmt->bindParam(':type', $role_type);
        $roleStmt->bindParam(':user_id', $userId);

        if (!$roleStmt->execute()) {
            throw new Exception("Failed to assign role: " . $this->conn->errorInfo()[2]);
        }
    }

    private function showSuccessModal()
    {
        echo '<script type="text/javascript">
            $(document).ready(function() {
                $("#successModal").modal("show");
            });
            </script>';
    }

    public function getUsers()
    {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id)
    {
        // Ensure cascading deletes work correctly with related tables
        $query = "DELETE FROM users WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete user: " . $this->conn->errorInfo()[2]);
        }
    }
}