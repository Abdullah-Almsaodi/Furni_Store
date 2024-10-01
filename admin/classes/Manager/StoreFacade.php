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

    public function addProduct($name, $description, $price, $cat_id, $image)
    {
        return $this->productManager->addProduct($name, $description, $price, $cat_id, $image);
    }

    public function editProduct($id, $name, $description, $price, $cat_id, $image = null)
    {
        return $this->productManager->editProduct($id, $name, $description, $price, $cat_id, $image = null);
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
