<?php
// Incluir a configuração do banco de dados
include_once '../../app/Controllers/db.php';

// Classe de autenticação do administrador
class Authadm {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Verificar se o usuário existe e é um administrador
            $sql = "SELECT * FROM admins WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                session_start(); // Inicia a sessão
                $_SESSION['admin_id'] = $admin['id']; // Armazena o ID do administrador na sessão
                $_SESSION['admin_name'] = $admin['name']; // Armazena o nome do administrador

                // Redireciona para a página do painel administrativo
                header("Location: /Gataway2/resources/viewsadm/admin_panel.php");
                exit;
            } else {
                return "E-mail ou senha incorretos.";
            }
        }
        return null;
    }
}

// Criar uma instância do controlador de autenticação e processar o login
$authController = new Authadm($conn);
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
            <h2>Fazer login ADMIN</h2>

            <?php if (!empty($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Entrar</button>
            </form>

            <div class="links">
                <a href="#">Esqueceu a senha?</a><br><br>
                <a href="registeradm.php">Crie sua conta ➔</a><br> 
                
                <a href="login.php">Aluno ➔</a>

            </div>
        </div>
    </div>
</body>
</html>
