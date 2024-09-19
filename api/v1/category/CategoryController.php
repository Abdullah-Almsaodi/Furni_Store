<?php

class CategoryController
{
    private $categoryManager;
    private $responseHandler;

    public function __construct($categoryManager, $responseHandler)
    {
        $this->categoryManager = $categoryManager;
        $this->responseHandler = $responseHandler;
    }

    public function handleRequest($method, $request)
    {
        switch ($method) {
            case 'GET':
                $this->getCategory($request);
                break;
            default:
                echo json_encode(['message' => 'Method not allowed'], JSON_PRETTY_PRINT);
        }
    }

    private function getCategory($request)
    {
        // Check if the ID is provided in the request
        if (isset($request[1])) {
            // Try to retrieve the user by ID
            $category = $this->categoryManager->getCategoryById($request[1]);

            // Check if user is found
            if ($category) {
                $this->responseHandler->handleSuccess($category, 'category found');
            } else {
                // Handle case where user is not found
                $this->responseHandler->handleError(['errors' => ['category not found']], 'category not found');
            }
        } else {
            // Retrieve all users if no ID is provided
            $categories = $this->categoryManager->getCategories();

            // Check if users list is empty
            if (!empty($categories)) {
                $this->responseHandler->handleSuccess($categories, 'category found');
            } else {
                // Handle case where no users are found
                $this->responseHandler->handleError(['errors' => ['No category found']], 'No category found');
            }
        }
    }
}
