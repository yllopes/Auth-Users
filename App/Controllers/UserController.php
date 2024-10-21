<?php
namespace Proj\JS\Controllers;

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

use Proj\JS\Models\UserModel;
use Proj\JS\Middleware\AuthMiddleware;

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';

            $token = $this->userModel->authenticate($username, $password);
            if ($token) {
                setcookie('token', $token, time() + 3600, '/');
                echo json_encode(['token' => $token]);
            } else {
                http_response_code(401);
                echo json_encode(['message' => 'Senha ou Usuario errado']);
            }
            return;
        }

        include '../Views/Login.php'; 
    }

    public function dashboard() {
        AuthMiddleware::authenticate();
        header('Content-Type: application/json');
    
        echo json_encode(['message' => 'Bem-vindo professor!']);
        exit; 
    }
    

    public function logout() {
        setcookie('token', '', time() - 3600, "/");
        header("Location: /public/index.php");
        exit;
    }
}
