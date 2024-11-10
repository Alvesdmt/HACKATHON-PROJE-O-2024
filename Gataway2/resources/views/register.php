<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Cashtime</title>
    <link rel="stylesheet" href="../../public/css/cadastro.css">
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <img src="../..\public\images\logo1.png" alt="Cashtime Logo" class="logo">
            <h2>Cadastre-se</h2>
            <form action="../../app/Controllers/RegisterController.php" method="POST">
    <label for="name">Nome</label>
    <input type="text" id="name" name="name" required>
    
    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" required>
    
    <label for="phone">Telefone</label>
    <input type="text" id="phone" name="phone">
    
    <label for="password">Senha</label>
    <input type="password" id="password" name="password" required minlength="6">
    
    <button type="submit">Cadastrar</button>
</form>

            <div class="links">
                <a href="login.php">Já possuo uma conta ➔</a>
            </div>
        </div>
    </div>
</body>
</html>
