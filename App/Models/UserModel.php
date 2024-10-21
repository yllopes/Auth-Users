<?php
namespace Proj\JS\Models;

require_once __DIR__ . '/../Config/Config.php';
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

class UserModel {
    private $db;

    public function __construct() {
        try {
            $this->db = new \PDO("sqlite:" . DB_PATH);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['password'])) {
            $token = $this->generateToken($username);
            return $token;
        }
        return null;
    }

    private function generateToken($username) {
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'username' => $username
        ];
        try {
            return JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
        } catch (\Exception $e) {

            error_log('Error generating token: ' . $e->getMessage());
            return null;
        }
    }

    public function isLoggedIn($token) {
        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));
            return true;
            
        } catch (ExpiredException $e) {
            return false;

        } catch (\Exception $e) {
            return false; 
        }
    }
}
