<?php

use Proj\JS\Controllers\UserController;
use Proj\JS\Middleware\AuthMiddleware;

$userController = new UserController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($uri === '/login' && $requestMethod === 'POST') {
    $userController->login();
} elseif ($uri === '/dashboard' && $requestMethod === 'GET') {
    AuthMiddleware::authenticate();
    $userController->dashboard();
} elseif ($uri === '/logout' && $requestMethod === 'GET') {
    $userController->logout();
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Rota não encontrada.']);
}
