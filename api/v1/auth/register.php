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

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';
$role_id = $data['role_id'] ?? 1;

$userManager = new UserManager($userRepository);
$result = $userManager->addUser($username, $email, $password, $confirm_password, $role_id);

if ($result['success']) {
    echo json_encode(["message" => "User registered successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["errors" => $result['errors']]);
}
