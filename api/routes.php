<?php
$request = str_replace('/New-Furni/api', '', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];

// Routing based on URL and method
switch ($request) {
    case '/v1/auth/login':
        require __DIR__ . '/v1/Auth/Login.php';
        break;
    case '/v1/auth/register':
        require __DIR__ . '/v1/Auth/Register.php';
        break;
    case '/v1/user':
        require __DIR__ . '/v1/User/User.php';
        break;
    case '/v1/product':
        require __DIR__ . '/v1/Product/Product.php';
        break;
    case '/v1/category':
        require __DIR__ . '/v1/Category/Category.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
        break;
}
