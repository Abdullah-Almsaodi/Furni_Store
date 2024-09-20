<?php
require_once '../../../admin/pages/config.php'; // Database configuration
require_once '../../../admin/classes/Database.php';
require_once '../../../admin/classes/Manager/UserManager.php';
require_once '../../../admin/classes/Repository/UserRepository.php';
require_once '../../../admin/middleware/ResponseHandler.php';
require_once '../../../vendor/autoload.php'; // JWT
// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();
$userRepository = new UserRepository($conn);
$responseHandler = new ResponseHandler();

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$password1 = $data['password1'] ?? '';
$role = $data['role'] ?? 1;

$userManager = new UserManager($userRepository);
$result = $userManager->addUser($username, $email, $password, $password1, $role);

if ($result['success']) {
    http_response_code(200);

    $responseHandler->handleSuccess($result, 'User registered successfully');


    // echo json_encode(["message" => "User registered successfully"]);
} else {
    http_response_code(400);
    // Handle case where user is not found
    $responseHandler->handleError(["errors" => $result['errors']], 'User not found');
}
