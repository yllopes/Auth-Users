<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 

define('DB_PATH', __DIR__ . '/../../data/proj_js.db');
define('JWT_SECRET_KEY', 'sua_chave_secreta_complexa');
try {
    $db = new PDO("sqlite:" . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $username = 'usuario';
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $passwordHash = password_hash('senha', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->execute();
    }

} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit;
}
