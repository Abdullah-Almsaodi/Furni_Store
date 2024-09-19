<?php

class UserController
{
    private $userManager;
    private $responseHandler;

    public function __construct($userManager, $responseHandler)
    {
        $this->userManager = $userManager;
        $this->responseHandler = $responseHandler;
    }

    public function handleRequest($method, $request)
    {
        switch ($method) {
            case 'GET':
                $this->getUser($request);
                break;
            case 'POST':
                $this->createUser();
                break;
            case 'PUT':
                $this->updateUser($request);
                break;
            case 'DELETE':
                $this->deleteUser($request);
                break;
            default:
                echo json_encode(['message' => 'Method not allowed'], JSON_PRETTY_PRINT);
        }
    }

    private function getUser($request)
    {
        // Check if the ID is provided in the request
        if (isset($request[1])) {
            // Try to retrieve the user by ID
            $user = $this->userManager->getUserById($request[1]);

            // Check if user is found
            if ($user) {
                $this->responseHandler->handleSuccess($user, 'User found');
            } else {
                // Handle case where user is not found
                $this->responseHandler->handleError(['errors' => ['User not found']], 'User not found');
            }
        } else {
            // Retrieve all users if no ID is provided
            $users = $this->userManager->getUsers();

            // Check if users list is empty
            if (!empty($users)) {
                $this->responseHandler->handleSuccess($users, 'Users found');
            } else {
                // Handle case where no users are found
                $this->responseHandler->handleError(['errors' => ['No users found']], 'No users found');
            }
        }
    }



    private function createUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);




        $result = $this->userManager->addUser($data['username'], $data['email'], $data['password'], $data['password1'], $data['role']);
        if ($result['success']) {
            $this->responseHandler->handleSuccess([], 'User created');
        } else {
            $this->responseHandler->handleError($result['errors']);
        }
    }

    private function updateUser($request)
    {
        if (!isset($request[1])) {
            $this->responseHandler->handleError(['message' => 'User ID is required']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);




        $result = $this->userManager->updateUser($request[1], $data['username'], $data['email'], $data['password'], $data['password1'], $data['role'], $data['active']);
        if ($result['success']) {
            $this->responseHandler->handleSuccess([], 'User updated');
        } else {
            $this->responseHandler->handleError($result['errors']);
        }
    }

    private function deleteUser($request)
    {
        if (!isset($request[1])) {
            $this->responseHandler->handleError(['message' => 'User ID is required']);
            return;
        }

        $this->userManager->deleteUser($request[1]);
        $this->responseHandler->handleSuccess([], 'User deleted');
    }
}
