<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers for API response
header("Content-Type: application/json");

// Include necessary files
require_once '../admin/pages/config.php';
require_once '../admin/classes/Database.php';
require_once '../admin/classes/Repository/UserRepository.php';
require_once '../admin/classes/Manager/UserManager.php';
require_once '../admin/classes/Repository/CategoryRepository.php';
require_once '../admin/classes/Manager/CategoryManager.php';
require_once '../admin/middleware/Validation.php';
require_once '../admin/middleware/ResponseHandler.php';

// Initialize Database connection
$dbInstance = Database::getInstance();
$conn = $dbInstance->getConnection();

// Initialize repositories and managers
$userRepository = new UserRepository($conn);
$userManager = new UserManager($userRepository);

$categoryRepository = new CategoryRepository($conn);
$categoryManager = new CategoryManager($categoryRepository);
$responseHandler = new ResponseHandler();
// Capture the HTTP request method and URL parameters
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Route requests to appropriate controller
if ($request[0] === 'users') {
    require_once 'v1/UserController.php';
    $userController = new UserController($userManager, $responseHandler);
    $userController->handleRequest($method, $request);
} elseif ($request[0] === 'category') {
    require_once 'v1/CategoryController.php';
    $categoryController = new CategoryController($categoryManager, $responseHandler);
    $categoryController->handleRequest($method, $request);
} else {
    echo json_encode(['message' => 'Invalid endpoint'], JSON_PRETTY_PRINT);
}
