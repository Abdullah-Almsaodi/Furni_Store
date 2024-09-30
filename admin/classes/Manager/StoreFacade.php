<?php

require_once 'ProductManager.php';
require_once 'CategoryManager.php';


class StoreFacade
{

    private $productManager;
    private $categoryManager;

    public function __construct()
    {
        require_once 'config.php'; // Database configuration
        require_once '../classes/Database.php';
        require_once '../classes/Manager/CategoryManager.php';
        require_once '../classes/Repository/CategoryRepository.php';
        require_once '../classes/Manager/ProductManager.php';
        require_once '../classes/Repository/ProductRepository.php';

        // Initialize Database
        $dbInstance = Database::getInstance();
        $conn = $dbInstance->getInstance()->getConnection();

        // Initialize repositories with dependencies
        $productRepository = new ProductRepository($conn);
        $productManager = new ProductManager($productRepository);

        $categoryRepository = new CategoryRepository($conn);
        $categoryManager = new CategoryManager($categoryRepository);
        $this->productManager = $productManager;
        $this->categoryManager = $categoryManager;
    }

    // إدارة المنتجات

    public function editProduct($id, $name, $description, $price, $cat_id, $image)
    {
        return $this->productManager->addProduct($id, $name, $description, $price, $cat_id, $image);
    }

    public function updateProduct(int $id, string $name, string $description, float $price, int $category_id,  $imageData)
    {
        return $this->productManager->editProduct($id, $name, $description, $price, $category_id, $imageData);
    }

    public function deleteProduct(int $id)
    {
        return $this->productManager->deleteProduct($id);
    }

    public function listProducts()
    {
        return $this->productManager->getProducts();
    }

    public function findProduct(int $id)
    {
        return $this->productManager->getProductById($id);
    }

    public function validateProductImage($image)
    {
        return $this->productManager->validateProductImage($image);
    }

    public function validateProductData($name, $description, $price, $cat_id)
    {
        return $this->productManager->validateProductData($name, $description, $price, $cat_id);
    }

    // إدارة الفئات

    public function addCategory(string $name, string $description)
    {
        return $this->categoryManager->addCategory($name, $description);
    }

    public function updateCategory(int $id, string $name, string $description)
    {
        return $this->categoryManager->editCategory($id, $name, $description);
    }

    public function deleteCategory(int $id)
    {
        return $this->categoryManager->deleteCategory($id);
    }

    public function listCategories()
    {
        return $this->categoryManager->getCategories();
    }

    public function findCategory(int $id)
    {
        return $this->categoryManager->getCategoryById($id);
    }
}
