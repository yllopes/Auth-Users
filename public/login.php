<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../App/Config/Config.php';

use Proj\JS\Models\UserModel;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $userModel = new UserModel();
    $token = $userModel->authenticate($username, $password);

    if ($token) {
        setcookie('token', $token, time() + 3600, "/");
        echo json_encode(['token' => $token]);
    
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Usuário ou senha inválidos.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Método não permitido.']);
}
