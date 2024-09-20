<?php

require_once '../../../admin/pages/config.php'; // Database configuration
require_once '../../../admin/classes/Database.php';
require_once '../../../admin/classes/Manager/CategoryManager.php';
require_once '../../../admin/classes/Repository/CategoryRepository.php';
require_once '../../../vendor/autoload.php'; // JWT
// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();

// Initialize repositories with dependencies
$categortyRepository = new CategoryRepository($conn);


use \Firebase\JWT\JWT;

$secret_key = "1234";
$headers = getallheaders();
$jwt = $headers['Authorization'] ?? '';

if ($jwt) {
    try {
        $decoded = JWT::decode($jwt, $secret_key);
        $user_data = (array) $decoded->data;

        $categoryManager = new CategoryManager($categortyRepository);
        $categories = $categoryManager->getCategoriesf();

        echo json_encode([
            "message" => "Categories fetched successfully",
            "categories" => $categories
        ]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Access denied", "error" => $e->getMessage()]);
    }
} else {
    http_response_code(401);
    echo json_encode(["message" => "No token provided"]);
}
