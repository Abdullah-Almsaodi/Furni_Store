<?php
class CategoryManager
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }



    public function addCategory($name, $description)
    {
        // Validate the category data
        $errors = $this->validateCategoryData($name, $description);

        if (!empty($errors)) {
            // Return errors if validation fails
            // return $errors;
            return ['success' => false, 'errors' => $errors];
        }

        // If validation passes, insert the category into the database
        $sql = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            // return true; // Indicate success
            return ['success' => true];
        } else {
            // return false; // Indicate failure
            return ['success' => false, 'errors' => ['general' => 'Failed to add categories']];
        }
    }

    public function validateCategoryData($name, $description)
    {
        $errors = [];

        // Validate name
        if (empty($name)) {
            $errors['cname'] = "Category Name is required";
        } elseif (is_numeric($name)) {
            $errors['cname'] = "Enter String Name of Category";
        }

        // Validate name
        if (empty($description)) {
            $errors['cdescription'] = "Category description is required ";
        }

        return $errors;
    }


    public function editCategory($id, $name, $description)
    {

        // Validate the category data
        $errors = $this->validateCategoryData($name, $description);

        if (!empty($errors)) {
            // Return errors if validation fails
            return ['success' => false, 'errors' => $errors];
        }

        $query = "UPDATE categories SET name = :name, description = :description WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);


        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to update categories']];
        }
    }

    public function deleteCategory($id)
    {
        $query = "DELETE FROM categories WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getCategories()
    {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCategoriesById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE category_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
