<?php
class CategoryRepository
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

    public function find($id)
    {
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll()
    {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
