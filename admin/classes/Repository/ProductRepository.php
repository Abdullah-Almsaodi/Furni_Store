<?php
class ProductRepository
{

    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function addProduct(string $name, string $description, float $price, $cat_id, string $image): bool
    {
        $sql = "INSERT INTO products (name,price,description,image,category_id) VALUES (:name, :price, :description, :image, :cat_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':cat_id', $cat_id);

        return $stmt->execute();
    }

    public function updateProduct(int $id, string $name, string $description, float $price, int $cat_id, string $image): bool
    {
        $query = "UPDATE products SET name = :name,  price = :price, description = :description, category_id = :category_id , image = :image WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $cat_id);
        $stmt->bindParam(':image', $image);
        return $stmt->execute();
    }

    public function deleteProduct(int $product_id): bool
    {
        $query = "DELETE FROM products WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function getAllProducts(): array
    {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById(int $product_id): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE product_id = :id");
        $stmt->execute(['id' => $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
