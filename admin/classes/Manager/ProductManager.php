<?php
class ProductManager //tset commit
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->getInstance()->getConnection();
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


        if ($stmt->execute()) {
            // return true; // Indicate success
            return ['success' => true];
        } else {
            // return false; // Indicate failure
            return ['success' => false, 'errors' => ['general' => 'Failed to add prdoct']];
        }
    }

    public function validateProductdata($name, $price, $description, $category_id)
    {

        $errors = [];

        if (empty($_POST["name"])) {
            $errors['nameE'] = " Name is required";
        } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $errors['nameE'] = "Only letters and white space allowed";
            }
        }



        if (empty($_POST["price"])) {
            $errors['priceE'] = "price Number is required";
        } else {
            $price = test_input($_POST["price"]);
            // check if phone only contains numbers
            if (!is_numeric($price)) {
                $errors['priceE'] = "Only numeric values allowed";
            }
        }



        if (empty($_POST["description"])) {
            $errors['descriptionE'] = "About me is required";
        } else {
            $description = test_input($_POST["description"]);
            // check if about me only contains letters, whitespace, and numbers
            if (!preg_match("/^[a-zA-Z0-9\s.,'-]*$/", $description)) {
                $errors['descriptionE'] = "Only letters, numbers, whitespace, commas, apostrophes, and hyphens allowed in the about me field";
            }
        }


        if (empty($_POST["category_id"])) {
            $errors['cateE'] = " Role is required";
        } else {
            $category_id = test_input($_POST["category_id"]);
        }
    }








    public function editProduct($id, $name, $description, $price, $category_id, $image_path)
    {
        $query = "UPDATE products SET name = :name, description = :description, price = :price, category_id = :category_id, image_path = :image_path WHERE product_id	 = :id";
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

    public function getProducts(): array
    {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id)
    {
        $stmt = $this->conn->prepare("select * from products where product_id=:product_id");
        $stmt->execute(['product_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
