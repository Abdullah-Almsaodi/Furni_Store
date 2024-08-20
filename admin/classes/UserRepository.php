<?php
// admin/classes/UserRepository.php

class UserRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function isEmailExist($email)
    {
        $query = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function addUser($name, $email, $hashedPassword, $activationToken)
    {
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

        return $this->conn->lastInsertId();
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete user: " . $this->conn->errorInfo()[2]);
        }
    }
}
