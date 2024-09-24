<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../../admin/pages/config.php'; // Database configuration
require_once '../../../admin/classes/Database.php';
require_once '../../../admin/classes/Manager/ProductManager.php';
require_once '../../../admin/classes/Repository/ProductRepository.php';
require_once '../../../admin/middleware/ResponseHandler.php'; // Response Handler for standard responses
require_once '../../../vendor/autoload.php'; // For JWT

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key; // Important for key handling

$method = $_SERVER['REQUEST_METHOD'];
$productId = isset($_GET['id']) ? intval($_GET['id']) : null;
$secret_key = "123"; // Secret key for JWT (replace with env variable in production)
$issuer = "furni-store"; // Issuer of the token
$Role_type = "Admin"; // Issuer of the token

// JWT Authorization
$headers = getallheaders();
$jwt = $headers['Authorization'] ?? '';
$jwt = str_replace('Bearer ', '', $jwt); // Remove 'Bearer ' prefix


// Initialize Database and ProductManager
$dbInstance = Database::getInstance();
$conn = $dbInstance->getConnection();
$productRepository = new ProductRepository($conn);
$productManager = new ProductManager($productRepository);
$responseHandler = new ResponseHandler();

switch ($method) {
    case 'GET':
        if ($productId) {
            // Fetch a single product by ID
            $product = $productManager->getProductById($productId);
            if ($product) {
                $responseHandler->handleSuccess($product, 'Product found');
            } else {
                $responseHandler->handleError(['errors' => ['Product not found']], 'Product not found');
            }
        } else {
            // Fetch all products
            $products = $productManager->getProducts();
            if (!empty($products)) {
                $responseHandler->handleSuccess($products, 'Products found');
            } else {
                $responseHandler->handleError(['errors' => ['No products found']], 'No products found');
            }
        }
        break;

    case 'POST':
        // Read incoming data
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $price = $data['price'] ?? '';
        $description = $data['description'] ?? '';
        $cat_id = $data['category_id'] ?? '';
        $image = $data['image'] ?? '';


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

                // Check if the user has the Admin role
                if ($decoded->data->role !== 'Admin') {
                    http_response_code(403);
                    $responseHandler->handleError(['errors' => ['authorization']], "Access denied, insufficient permissions");
                    exit();
                }

                // Create a new product
                $result = $productManager->addProduct($name, $description, $price, $image, $cat_id);
                if ($result['success']) {
                    http_response_code(201); // Product created
                    $responseHandler->handleSuccess($result, 'Product created successfully');
                } else {
                    http_response_code(400); // Bad request
                    $responseHandler->handleError($result, "Product not created");
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
        // Ensure product ID is provided
        if (!$productId) {
            http_response_code(400);
            $responseHandler->handleError(['errors' => ['Product ID required']], "Product ID is required");
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

                // Check if the user has the Admin role
                if ($decoded->data->role !== 'Admin') {
                    http_response_code(403);
                    $responseHandler->handleError(['errors' => ['authorization']], "Access denied, insufficient permissions");
                    exit();
                }
                // Update the product
                $result = $productManager->editProduct($productId, $name, $description, $price, $image, $cat_id);
                if ($result['success']) {
                    $responseHandler->handleSuccess($result, "Product updated successfully");
                } else {
                    http_response_code(400);
                    $responseHandler->handleError($result['errors'], "Product not updated");
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
        // Ensure product ID is provided
        if (!$productId) {
            http_response_code(400);
            $responseHandler->handleError(['errors' => ['Product ID required']], "Product ID is required");
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


                // Check if the user has the Admin role
                if ($decoded->data->role !== 'Admin') {
                    http_response_code(403);
                    $responseHandler->handleError(['errors' => ['authorization']], "Access denied, insufficient permissions");
                    exit();
                }
                // Delete the product
                $result = $productManager->deleteProduct($productId);
                if ($result['success']) {
                    $responseHandler->handleSuccess($result, "Product deleted successfully");
                } else {
                    http_response_code(404);
                    $responseHandler->handleError(['errors' => ['Product not found']], "Product not found");
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
