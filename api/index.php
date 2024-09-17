<?php

// api/index.php
header("Content-Type: application/json");

require_once '../admin/pages/config.php';
require_once '../admin/classes/Database.php';
require_once '../admin/classes/Repository/UserRepository.php';
require_once '../admin/classes/Manager/UserManager.php';

// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();



// Initialize repositories with dependencies
$userRepository = new UserRepository($conn);
$userManager = new UserManager($userRepository);

// Capture the HTTP request method and URL parameters
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Route requests to appropriate handler
if ($request[0] === 'user') {


    // $userManager = new UserManager();

    if ($method === 'GET') {
        // Handle fetching user(s)
        if (isset($request[1])) {
            $user = $userManager->getUserById($request[1]);

            if (($user['success'])) {

                echo json_encode($user);
                echo json_encode(["status" => "success", 'message' => 'User Fond']);
            } else {

                $errors = $user['errors'];
                echo json_encode(["status" => "failure", 'message' => $errors]);
            }
        } else {
            $users = $userManager->getUsers();
            if (($users['success'])) {

                echo json_encode($users);
                echo json_encode(["status" => "success", 'message' => 'User Fond']);
            } else {

                $errors = $users['errors'];
                echo json_encode(["status" => "failure", 'message' => $errors]);
            }
        }
    } elseif ($method === 'POST') {
        // Handle creating a new user
        $data = json_decode(file_get_contents('php://input'), true);

        $name = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $password1 = $data['password1'] ?? '';
        $role_id = $data['role'] ?? 0;




        $add = $userManager->addUser($name, $email, $password, $password1, $role_id);
        if (($add['success'])) {
            echo json_encode(["status" => "success", 'message' => 'User created']);
        } else {

            $errors = $add['errors'];
            echo json_encode(["status" => "failure", 'message' => $errors]);
        }
    } elseif ($method === 'PUT') {
        // Handle updating user details
        if (isset($request[1])) {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? '';
            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $password1 = $data['password1'] ?? '';
            $role_id = $data['role_id'] ?? 0;
            $active = $data['active'] ?? 1;

            $update = $userManager->updateUser($id, $name, $email, $password, $password1, $role_id, $active);
            if (($update['success'])) {
                echo json_encode(["status" => "success", 'message' => 'User updated']);
            } else {

                $errors = $add['errors'];
                echo json_encode(["status" => "failure", 'message' => $errors]);
            }
        }
    } elseif ($method === 'DELETE') {
        // Handle deleting a user
        if (isset($request[1])) {
            $userManager->deleteUser($request[1]);
            echo json_encode(['message' => 'User deleted']);
        }
    }
}
