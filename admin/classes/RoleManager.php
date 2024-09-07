<?php
class RoleManager
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

    // Add a new role to the database
    public function addRole(string $role_name): void
    {
        $query = "INSERT INTO roles (role_name) VALUES (:role_name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_name', $role_name);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add role: " . implode(', ', $stmt->errorInfo()));
        }
    }

    // Assign a role to a user
    public function assignRoleToUser(int $user_id, int $role_id): void
    {
        $query = "UPDATE users SET role_id = :role_id WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to assign role: " . implode(', ', $stmt->errorInfo()));
        }
    }

    // Retrieve all roles from the database
    public function getRoles(): array
    {
        $query = "SELECT * FROM roles";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a specific role by ID
    public function getRoleById(int $role_id): array
    {
        $query = "SELECT * FROM roles WHERE role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}