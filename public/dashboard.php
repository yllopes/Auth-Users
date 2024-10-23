<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../App/Config/Config.php';

use Proj\JS\Middleware\AuthMiddleware;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$authResult = AuthMiddleware::authenticate();


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
   
    echo json_encode([
        'message' => 'Funcionando',
        'user' => $authResult->username
    ]);
    exit; 
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const token = localStorage.getItem('token');

            
            if (!token) {
                alert('Você não está autenticado. Redirecionando para o login.');
                window.location.href = '/public/index.php';
                return; 
            }

            async function fetchDashboardData() {
                const token = localStorage.getItem('token');
                console.log("Token enviado:", token);
                try {
                    const response = await fetch('/public/dashboard.php', {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token, 
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const responseText = await response.text();
                    console.log("Response Text:", responseText);

                    if (!response.ok) {
                        throw new Error('Falha ao buscar dados do dashboard');
                    }

                    
                    if (responseText.startsWith('<')) {
                        throw new Error('Resposta inesperada: HTML foi recebido em vez de JSON');
                    }

                    const data = JSON.parse(responseText); 
                    console.log(data); 

                    
                    document.getElementById('welcome-message').innerText = `Espero que esteja bem, ${data.user}`;
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro ao buscar dados do dashboard: ' + error.message);
                }
            }

           
            fetchDashboardData();
        });

        function logout() {
            
            localStorage.removeItem('token');
            alert('Logout realizado com sucesso!');
            window.location.href = '/public/index.php'; 
        }
    </script>
</head>
<body>
    <h1>Bem-vindo ao dash :)</h1>
    <h2 id="welcome-message"></h2> 
    <p><a href="#" onclick="logout()">Logout</a></p>
</body>
</html>
