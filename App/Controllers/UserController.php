<?php
namespace Proj\JS\Controllers;

require_once __DIR__ . '/../Models/UserModel.php';

use Proj\JS\Models\UserModel;
use Proj\JS\Middleware\AuthMiddleware;
use Firebase\JWT\JWT;


class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login($request, $response) {
        $params = json_decode($request->getBody(), true);
        $username = $params['username'];
        $password = $params['password'];
    
        $user = $this->userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user->password)) {
            return $response->withJson(['message' => 'Credenciais inválidas'], 401);
        }
        
        
    
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'username' => $user->username
        ];
    
        $token = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
    
        return $response->withJson(['token' => $token]);
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
