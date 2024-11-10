<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// app/Controllers/RegisterController.php

include_once __DIR__ . '/db.php';

class RegisterController {
    public function register() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            try {
                // Verificar se o email já existe
                $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = :email");
                $checkEmail->bindParam(':email', $email);
                $checkEmail->execute();

                if ($checkEmail->rowCount() > 0) {
                    echo "Erro: o email já está em uso. Tente com outro email.";
                    return;
                }

                // Inserir novo usuário
                $sql = "INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)";
                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':password', $password);

                if ($stmt->execute()) {
                    header("Location: /Gataway2/resources/views/loginadm.php");
                    exit;
                } else {
                    echo "Erro ao cadastrar usuário.";
                }
            } catch (PDOException $e) {
                echo "Erro no registro: " . $e->getMessage();
            }
        }
    }
}

$registerController = new RegisterController();
$registerController->register();
