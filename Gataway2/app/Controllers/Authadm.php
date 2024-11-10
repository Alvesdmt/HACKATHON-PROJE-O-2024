<?php
// app/Controllers/AuthController.php

include_once __DIR__ . '/db.php';

class AuthController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar se os campos de email e senha foram enviados
            if (empty($_POST['email']) || empty($_POST['password'])) {
                return "Por favor, preencha todos os campos.";
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            // Buscar o usuário pelo email
            $sql = "SELECT * FROM admins WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar se o usuário foi encontrado e a senha está correta
            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id']; // Armazenar o ID do usuário
                $_SESSION['user_name'] = $user['name']; // Armazenar o nome do usuário na sessão

                // Redirecionar para o dashboard
                header("Location: resources\viewsadm\admin_document_upload.php");
                exit;
            } else {
                // Retorna uma mensagem de erro se as credenciais forem inválidas
                return "E-mail ou senha incorretos.";
            }
        }
        return null;
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: \Gataway2\resources\views\loginadm.php");
        exit;
    }
}
