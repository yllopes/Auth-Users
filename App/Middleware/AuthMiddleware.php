<?php

namespace Proj\JS\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use stdClass;

class AuthMiddleware {
    public static function authenticate(): stdClass {
        $token = $_COOKIE['token'] ?? null;

        if (!$token && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        if (!$token) {
            http_response_code(401);
            echo json_encode(['message' => 'Token não fornecido.']);
            exit;
        }

        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));

            if (!$decoded) {
                http_response_code(401);
                echo json_encode(['message' => 'Token inválido.']);
                exit;
            }

            return $decoded;
        } catch (ExpiredException $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Token expirado.']);
            exit;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Token inválido.']);
            exit;
        }
    }
}
