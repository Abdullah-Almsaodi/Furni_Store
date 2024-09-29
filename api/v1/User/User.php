<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../../admin/pages/config.php'; // Database configuration
require_once '../../../admin/classes/Database.php';
require_once '../../../admin/classes/Manager/UserManager.php';
require_once '../../../admin/classes/Repository/UserRepository.php';
require_once '../../../admin/middleware/ResponseHandler.php';
require_once '../../../vendor/autoload.php'; // For JWT

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key; // Important for key handling

$method = $_SERVER['REQUEST_METHOD'];
$userId = isset($_GET['id']) ? intval($_GET['id']) : null;
$secret_key = "123"; // Secret key for JWT (replace with env variable in production)
$issuer = "furni-store"; // Issuer of the token

// JWT Authorization
$headers = getallheaders();
$jwt = $headers['Authorization'] ?? '';
$jwt = str_replace('Bearer ', '', $jwt);  // Remove 'Bearer ' prefix


// Initialize Database and UserManager
$dbInstance = Database::getInstance();
$conn = $dbInstance->getConnection();
$userRepository = new UserRepository($conn);
$userManager = new UserManager($userRepository);
$responseHandler = new ResponseHandler();

switch ($method) {
    case 'GET':
        if ($userId) {
            // Fetch a single user by ID
            $user = $userManager->getUserById($userId);
            if ($user) {
                $responseHandler->handleSuccess($user, 'User found');
            } else {
                // Handle case where user is not found
                $responseHandler->handleError(['errors' => ['User not found']], 'User not found');
            }
        } else {
            // Fetch all users
            $users = $userManager->getUsers();

            // Check if users list is empty
            if (!empty($users)) {
                $responseHandler->handleSuccess($users, 'Users found');
            } else {
                // Handle case where no users are found
                $responseHandler->handleError(['errors' => ['No users found']], 'No users found');
            }
        }
        break;

    case 'POST':
        // Read incoming data
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $password1 = $data['password1'] ?? '';
        $role = $data['role'] ?? 1;

        // Create a new user
        // $result = $userManager->addUser($username, $email, $password, $password1, $role);
        // if ($result['success']) {
        //     http_response_code(201); // User created
        //     $responseHandler->handleSuccess($result, 'User created successfully');
        // } else {
        //     http_response_code(400); // Bad request
        //     $responseHandler->handleError($result, "User Not created ");
        // }


        if ($jwt) {
            try {
                // Decode the JWT with the correct key and algorithm
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                // Optionally validate claims like issuer
                if ($decoded->iss !== $issuer) {
                    http_response_code(401);
                    $responseHandler->handleError(['errors' => ['token']], "Invalid token issuer");
                    exit();
                }

                // Create a new user
                $result = $userManager->addUser($username, $email, $password, $password1, $role);
                if ($result['success']) {
                    http_response_code(201); // User created
                    $responseHandler->handleSuccess($result, 'User created successfully');
                } else {
                    http_response_code(400); // Bad request
                    $responseHandler->handleError($result, "User Not created ");
                }
            } catch (\Firebase\JWT\ExpiredException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Token expired");
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token']], "Invalid signature");
            } catch (Exception $e) {
                http_response_code(401); // Unauthorized
                $responseHandler->handleError(['errors' => ['token']], "Access denied, invalid token");
            }
        } else {
            http_response_code(401);
            $responseHandler->handleError(['errors' => ['token']], "Access denied, token not provided");
        }
        break;

    case 'PUT':
        // Ensure user ID is provided
        if (!$userId) {
            http_response_code(400); // Bad request
            $responseHandler->handleError(['errors' => ['User_Id' => 'User ID is required']], "User ID is required");
            exit;
        }

        $errors = array();

        // Read incoming data
        $data = json_decode(file_get_contents('php://input'), true);

        $user_id = $data['user_id'] ?? '';
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $password1 = $data['password1'] ?? '';
        $role = $data['role'] ?? 1;
        $active = $data['is_active'] ?? 1;

        if ($jwt) {
            try {
                // Decode the JWT
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                // Optionally validate claims like issuer
                if ($decoded->iss !== $issuer) {
                    http_response_code(401);
                    $responseHandler->handleError(['errors' => ['token' => 'Invalid token issuer']], "Invalid token issuer");
                    exit();
                }

                // Update the user
                $result = $userManager->updateUser($user_id, $username, $email, $password, $password1, $role, $active);
                if ($result['success']) {
                    http_response_code(200); // Success
                    $responseHandler->handleSuccess($result, 'User updated successfully');
                } else {
                    http_response_code(400); // Bad request
                    $responseHandler->handleError(['errors' => $result['errors']], "User not updated");
                }
            } catch (\Firebase\JWT\ExpiredException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token' => 'Token expired']], "Token expired");
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                http_response_code(401);
                $responseHandler->handleError(['errors' => ['token' => 'Invalid signature']], "Invalid signature");
            } catch (Exception $e) {
                http_response_code(401); // Unauthorized
                $responseHandler->handleError(['errors' => ['token' => 'Access denied, invalid token']], "Access denied, invalid token");
            }
        } else {
            http_response_code(401);
            $responseHandler->handleError(['errors' => ['token' => 'Access denied, token not provided']], "Access denied, token not provided");
        }
        break;

    case 'DELETE':
        // Ensure user ID is provided
        if (!$userId) {
            http_response_code(400); // Bad request
            echo json_encode(["message" => "User ID is required"]);
            exit();
        }




        // Delete the user
        $result = $userManager->deleteUser($userId);
        if ($result) {
            echo json_encode(["message" => "User deleted successfully"]);
        } else {
            http_response_code(404); // User not found
            echo json_encode(["message" => "User not found"]);
        }

        if ($jwt) {
            try {
                // Decode the JWT
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                // Optionally validate claims like issuer
                if ($decoded->iss !== $issuer) {
                    http_response_code(401);
                    echo json_encode(["message" => "Invalid token issuer"]);
                    exit();
                }

                // Delete the user
                $result = $userManager->deleteUser($userId);
                if ($result) {
                    echo json_encode(["message" => "User deleted successfully"]);
                } else {
                    http_response_code(404); // User not found
                    echo json_encode(["message" => "User not found"]);
                }
            } catch (\Firebase\JWT\ExpiredException $e) {
                http_response_code(401);
                echo json_encode(["message" => "Token expired"]);
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                http_response_code(401);
                echo json_encode(["message" => "Invalid signature"]);
            } catch (Exception $e) {
                http_response_code(401); // Unauthorized
                echo json_encode(["message" => "Access denied, invalid token"]);
            }
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Access denied, token not provided"]);
        }
        break;

    default:
        http_response_code(405); // Method not allowed
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
