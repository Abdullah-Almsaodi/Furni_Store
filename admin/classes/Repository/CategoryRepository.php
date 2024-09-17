<?php
class CategoryRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function addCategory(string $name, string $description): bool
    {
        $sql = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function updateCategory(int $id, string $name, string $description): bool
    {
        $query = "UPDATE categories SET name = :name, description = :description WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteCategory(int $id): bool
    {
        $query = "DELETE FROM categories WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAllCategories(): array
    {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById(int $id): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE category_id = :id");
        $stmt->execute(['id' => $id]);
        
        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return an empty array if no result is found
        return $result !== false ? $result : [];
    }
}    
