<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../../admin/pages/config.php'; // Database configuration
require_once '../../../admin/classes/Database.php';
require_once '../../../admin/classes/Manager/CategoryManager.php';
require_once '../../../admin/classes/Repository/CategoryRepository.php';
require_once '../../../admin/middleware/ResponseHandler.php'; // Response Handler for standard responses
require_once '../../../vendor/autoload.php'; // For JWT

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key; // Important for key handling

$method = $_SERVER['REQUEST_METHOD'];
$categoryId = isset($_GET['id']) ? intval($_GET['id']) : null;
$secret_key = "123"; // Secret key for JWT (replace with env variable in production)
$issuer = "furni-store"; // Issuer of the token

// JWT Authorization
$headers = getallheaders();
$jwt = $headers['Authorization'] ?? '';
$jwt = str_replace('Bearer ', '', $jwt); // Remove 'Bearer ' prefix


// Initialize Database and CategoryManager
$dbInstance = Database::getInstance();
$conn = $dbInstance->getConnection();
$categoryRepository = new CategoryRepository($conn);
$categoryManager = new CategoryManager($categoryRepository);
$responseHandler = new ResponseHandler();

switch ($method) {
    case 'GET':
        if ($categoryId) {
            // Fetch a single category by ID
            $category = $categoryManager->getCategoryById($categoryId);
            if ($category) {
                $responseHandler->handleSuccess($category, 'Category found');
            } else {
                $responseHandler->handleError(['errors' => ['Category not found']], 'Category not found');
            }
        } else {
            // Fetch all categories
            $categories = $categoryManager->getCategories();
            if (!empty($categories)) {
                $responseHandler->handleSuccess($categories, 'Categories found');
            } else {
                $responseHandler->handleError(['errors' => ['No categories found']], 'No categories found');
            }
        }
        break;

    case 'POST':
        // Read incoming data
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';


        if ($jwt) {
            try {
                // Decode the JWT with the correct key and algorithm
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                // Validate token issuer
                if ($decoded->iss !== $issuer) {
                    http_response_code(401);
                    $responseHandler->handleError(['errors' => ['token']], "Invalid token issuer");
                    exit();
                }

                // Create a new category
                $result = $categoryManager->addCategory($name, $description);
                if ($result['success']) {
                    http_response_code(201); // Category created
                    $responseHandler->handleSuccess($result, 'Category created successfully');
                } else {
                    http_response_code(400); // Bad request
                    $responseHandler->handleError($result, "Category not created");
                }
            } catch (\Firebase\JWT\ExpiredException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Token expired");
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Invalid signature");
            } catch (Exception $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Access denied, invalid token");
            }
        } else {
            http_response_code(401);
            $responseHandler->handleError(['errors' => ['token']], "Access denied, token not provided");
        }
        break;

    case 'PUT':
        // Ensure category ID is provided
        if (!$categoryId) {
            http_response_code(400);
            $responseHandler->handleError(['errors' => ['Category ID required']], "Category ID is required");
            exit();
        }

        // Read incoming data
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';



        if ($jwt) {
            try {
                // Decode the JWT
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                // Validate token issuer
                if ($decoded->iss !== $issuer) {
                    http_response_code(401);
                    $responseHandler->handleError(['errors' => ['token']], "Invalid token issuer");
                    exit();
                }

                // Update the category
                $result = $categoryManager->editCategory($categoryId, $name, $description);
                if ($result['success']) {
                    $responseHandler->handleSuccess($result, "Category updated successfully");
                } else {
                    http_response_code(400);
                    $responseHandler->handleError($result['errors'], "Category not updated");
                }
            } catch (\Firebase\JWT\ExpiredException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Token expired");
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Invalid signature");
            } catch (Exception $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Access denied, invalid token");
            }
        } else {
            http_response_code(401);
            $responseHandler->handleError(['errors' => ['token']], "Access denied, token not provided");
        }
        break;

    case 'DELETE':
        // Ensure category ID is provided
        if (!$categoryId) {
            http_response_code(400);
            $responseHandler->handleError(['errors' => ['Category ID required']], "Category ID is required");
            exit();
        }


        if ($jwt) {
            try {
                // Decode the JWT
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                // Validate token issuer
                if ($decoded->iss !== $issuer) {
                    http_response_code(401);
                    $responseHandler->handleError(['errors' => ['token']], "Invalid token issuer");
                    exit();
                }

                // Delete the category
                $result = $categoryManager->deleteCategory($categoryId);
                if ($result['success']) {
                    $responseHandler->handleSuccess($result, "Category deleted successfully");
                } else {
                    http_response_code(404);
                    $responseHandler->handleError(['errors' => ['Category not found']], "Category not found");
                }
            } catch (\Firebase\JWT\ExpiredException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Token expired");
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Invalid signature");
            } catch (Exception $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Access denied, invalid token");
            }
        } else {
            http_response_code(401);
            $responseHandler->handleError(['errors' => ['token']], "Access denied, token not provided");
        }
        break;

    default:
        http_response_code(405); // Method not allowed
        $responseHandler->handleError(['errors' => ['Method not allowed']], "Method not allowed");
        break;
}
