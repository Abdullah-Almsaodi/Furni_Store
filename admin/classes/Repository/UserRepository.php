<?php

class UserRepository
{
    private $conn;


    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function addUser($username, $email, $hashedPassword, $activationToken, $role_id)
    {

        // Insert new user
        $query = "INSERT INTO users (username, email, password, activation_token, role_id) 
                  VALUES (:username, :email, :password, :activation_token, :role_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':activation_token', $activationToken);
        $stmt->bindParam(':role_id', $role_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add user: " . $stmt->errorInfo()[2]);
        }

        return $this->conn->lastInsertId();
    }


    public function updateUser($id, $name, $email, $role_id, $active)
    {

        // Update user in database
        $query = "UPDATE users SET username = :name, email = :email, password = :password, role_id = :role, is_active = :active WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role_id);
        $stmt->bindParam(':active', $active);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update user: " . $stmt->errorInfo()[2]);
        }

        return $stmt->execute();
    }


    public function isEmailExist($email): bool
    {
        $query = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }


    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers(): array
    {
        $query = "SELECT u.user_id, u.username, u.email, u.is_active, u.password, r.role_name 
                  FROM users u 
                  JOIN roles r ON u.role_id = r.role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function softDeleteUser($id)
    {
        $query = "UPDATE users SET delete_at = NOW() WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to soft delete user: " . $stmt->errorInfo()[2]);
        }
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete user: " . $stmt->errorInfo()[2]);
        }

        return $stmt->rowCount();
    }

    public function updateUserRole($user_id, $role_id)
    {
        $query = "UPDATE users SET role_id = :role_id WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update user role: " . $stmt->errorInfo()[2]);
        }
    }

    public function reactivateUser($user_id)
    {
        $query = "UPDATE users SET delete_at = NULL WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to reactivate user: " . $stmt->errorInfo()[2]);
        }
    }
}
