<?php
require_once '../../../admin/pages/config.php'; // Database configuration
require_once '../../../admin/classes/Database.php';
require_once '../../../admin/classes/Manager/UserManager.php';
require_once '../../../admin/classes/Repository/UserRepository.php';
require_once '../../../vendor/autoload.php'; // JWT
// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();
$userRepository = new UserRepository($conn);


use \Firebase\JWT\JWT;

$secret_key = "123";
$issuer = "furni-store";
$audience = "furni-store-users";
$iat = time();
$exp = $iat + 3600; // Token valid for 1 hour

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$userManager = new UserManager($userRepository);
$user = $userManager->loginUser($email, $password);

if ($user && $user['success'] && isset($user['user'])) {
    $token = array(
        "iss" => $issuer,
        "aud" => $audience,
        "iat" => $iat,
        "exp" => $exp,
        "data" => array(
            "id" => $user['user']['id'], // Updated path to 'id'
            "email" => $user['user']['email'], // Updated path to 'email'
            "role" => $user['user']['role_name'] // Updated to use 'role_name'
        )
    );
    $jwt = JWT::encode($token, $secret_key, 'HS256');
    echo json_encode([
        "message" => "Login successful",
        "token" => $jwt,
        "user" => array(
            "id" => $user['user']['id'], // Updated path to 'id'
            "email" => $user['user']['email'], // Updated path to 'email'
            "username" => $user['user']['username'], // Updated path to 'email'
            "role" => $user['user']['role_name'] // Updated to use 'role_name'
        )
    ]);
} else {
    http_response_code(401);
    echo json_encode(["errors" => $user['errors']]);
}
