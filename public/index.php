
<?php

//User correto = usuario
//Password = senha

require_once __DIR__ . '/../App/Controllers/UserController.php';

use Proj\JS\Controllers\UserController;

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userController->login();
    exit;
}


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script>
        function login() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            fetch('/public/index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ username: username, password: password }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    localStorage.setItem('token', data.token);
                    window.location.href = '/public/dashboard.php';
                } else {
                    alert('Usuário ou senha inválidos.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
    </script>
</head>
<body>
    <h2>Login</h2>
    <form id="loginForm" onsubmit="event.preventDefault(); login();">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
