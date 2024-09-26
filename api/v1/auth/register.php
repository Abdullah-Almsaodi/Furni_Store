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
    $userId = $result['user_id'];  // Retrieve the user ID

    // Generate JWT token after successful registration
    $payload = [
        'iss' => 'furni-store',
        'aud' => 'furni-store-users',
        'iat' => time(),
        'exp' => time() + (60 * 60), // Token expiration (1 hour)
        'data' => [
            'id' => $userId,  // Use the user ID
            'email' => $email,
            'role' => $role,
        ]
    ];

    $jwt = \Firebase\JWT\JWT::encode($payload, 'your-secret-key', 'HS256');

    // Return response with JWT token and user info
    http_response_code(201);
    $responseHandler->handleSuccess([
        'token' => $jwt,
        'user' => [
            'id' => $userId,
            'email' => $email,
            'username' => $username,
            'role' => $role,
        ]
    ], 'User registered successfully');
} else {
    http_response_code(400);
    $responseHandler->handleError(["errors" => $result['errors']], 'Registration failed');
}
