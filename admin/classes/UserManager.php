<?php
// admin/classes/UserManager.php 

class UserManager
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

    // Check if the email already exists in the database
    public function isEmailExist(string $email): bool
    {
        $query = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Hash the password using bcrypt
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Generate an activation token
    public function generateActivationToken(): string
    {
        return bin2hex(random_bytes(16));
    }

    // Add a new user with the given details
    public function addUser(string $username, string $email, string $password, int $role_id): void
    {
        // Validate email uniqueness
        if ($this->isEmailExist($email)) {
            throw new Exception("A user with that email already exists.");
        }

        // Hash the password and generate an activation token
        $hashedPassword = $this->hashPassword($password);
        $activationToken = $this->generateActivationToken();

        // Insert the new user into the database
        $query = "INSERT INTO users (username, email, password, activation_token, role_id) 
                  VALUES (:username, :email, :password, :activation_token, :role_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':activation_token', $activationToken);
        $stmt->bindParam(':role_id', $role_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add user: " . implode(', ', $stmt->errorInfo()));
        }
    }

    // Retrieve all users from the database
    public function getUsers(): array
    {
        $query = "SELECT u.user_id, u.username, u.email, u.is_active, r.role_name 
                  FROM users u 
                  JOIN roles r ON u.role_id = r.role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Soft delete a user by setting the delete_at timestamp
    public function softDeleteUser(int $user_id): void
    {
        $query = "UPDATE users SET delete_at = NOW() WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to soft delete user: " . implode(', ', $stmt->errorInfo()));
        }
    }

    // Permanently delete a user from the database
    public function deleteUser(int $user_id): void
    {
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete user: " . implode(', ', $stmt->errorInfo()));
        }
    }

    // Update a user's role
    public function updateUserRole(int $user_id, int $role_id): void
    {
        $query = "UPDATE users SET role_id = :role_id WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update user role: " . implode(', ', $stmt->errorInfo()));
        }
    }

    // Reactivate a user by clearing the delete_at timestamp
    public function reactivateUser(int $user_id): void
    {
        $query = "UPDATE users SET delete_at = NULL WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to reactivate user: " . implode(', ', $stmt->errorInfo()));
        }
    }
}
