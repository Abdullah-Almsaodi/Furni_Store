<?php
class ProductManager
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

    public function addProduct($name, $description, $price, $category_id, $image_path)
    {
        $query = "INSERT INTO products (name, description, price, category_id, image_path) VALUES (:name, :description, :price, :category_id, :image_path)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image_path', $image_path);
        return $stmt->execute();
    }

    public function editProduct($id, $name, $description, $price, $category_id, $image_path)
    {
        $query = "UPDATE products SET name = :name, description = :description, price = :price, category_id = :category_id, image_path = :image_path WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getProducts()
    {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
