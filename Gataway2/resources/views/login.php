<?php
// Incluir o controlador de autenticação e a configuração do banco de dados
include_once '../../app/Controllers/AuthController.php';
include_once '../..\app\Controllers\db.php';

// Criar uma instância do controlador e chamar o método login
$authController = new AuthController($conn);
$error = $authController->login();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cashtime</title>
    <link rel="stylesheet" href="../../public/css/login.css"> 
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="../../public/images/logo1.png" alt="Cashtime Logo" class="logo">
            <h2>Fazer login</h2>

            <?php if (!empty($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Entrar</button>
            </form>

            <div class="links">
                <a href="#">Esqueceu a senha?</a><br><br>
                <a href="register.php">Crie sua conta ➔</a><br>
                <a href="loginadm.php">Admin</a><br><br>

            </div>
        </div>
    </div>
</body>
</html>
