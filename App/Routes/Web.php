<?php

use Proj\JS\Controllers\UserController;
use Proj\JS\Middleware\AuthMiddleware;
use Proj\JS\Models\UserModel;

$userModel = new UserModel();
$userController = new UserController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($uri === '/login' && $requestMethod === 'POST') {
    $requestBody = file_get_contents('php://input');
    $request = json_decode($requestBody, true);
    $response = new stdClass();

    echo json_encode($userController->login($request, $response));
} elseif ($uri === '/dashboard' && $requestMethod === 'GET') {
    AuthMiddleware::authenticate();
    echo json_encode($userController->dashboard());
} elseif ($uri === '/logout' && $requestMethod === 'GET') {
    echo json_encode($userController->logout());
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Rota não encontrada.']);
}
